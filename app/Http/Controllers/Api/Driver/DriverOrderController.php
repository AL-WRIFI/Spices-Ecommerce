<?php

namespace App\Http\Controllers\Api\Driver;

use App\Enums\Order\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\OrderHistoryRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\Order\OrderDetailsResource;
use App\Http\Resources\Order\OrderResource;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderActivity;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Client\Request;

class DriverOrderController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $driver = $request->user();

        $today = now()->toDateString();
        $totalDeliveredToday =  Order::forDriver($driver->id)->where('status', OrderStatusEnum::DELIVERED->value)
            ->whereDate('updated_at', $today)
            ->count();

        $activeOrder = Order::forDriver($driver->id)->where('status', 'on_way')->first();

        return $this->successResponse(data:[
            'delivered_today' => $totalDeliveredToday,
            'active_order' => new OrderDetailsResource($activeOrder),
        ]);
    }

    public function orderHistory(OrderHistoryRequest $request)
    {
        $driver = $request->user();

        $query = Order::forDriver($driver->id)->filterByStatus($request->input('status'))
            ->filterByDateRange(
                $request->input('from_date'),
                $request->input('to_date')
            )
            ->filterByPaymentMethod($request->input('payment_method'))
            ->filterByAmountRange(
                $request->input('min_amount'),
                $request->input('max_amount')
            );

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return $this->successResponse(data:[ 'orders' => OrderResource::collection($orders) ]);
    }

    public function orderStats(Request $request)
    {
        $driver = $request->user();

        $stats = [
            'total_orders' => Order::forDriver($driver->id)->count(),
            'delivered_today' => Order::forDriver($driver->id)->filterByStatus('delivered')->whereDate('updated_at', today())->count(),
            'pending_orders' => Order::forDriver($driver->id)->whereIn('status', ['shipped', 'on_way'])->count(),
        ];

        return $this->successResponse(data:[ 'stats' => $stats ]);
    }

    public function show(Request $request, $orderId)
    {
        $driver = $request->user();
        $order = Order::where('id', $orderId)->where('driver_id', $driver->id)->first();

        if (!$order) {
            return $this->notFoundResponse('Order not found or not assigned to you.');
        }

        return $this->successResponse(data:[ 'order' => $order ]);
    }


    public function updateStatus(UpdateOrderStatusRequest $request)
    {
        $data = $request->validated();
        $driver = Driver::find($request->user()->id)->first();
        $order = Order::where('id', $data['order_id'])->where('driver_id', $driver->id)->first();
        
        if (!$order) {
            return $this->notFoundResponse('Order not found.');
        }


        if ($order->driver_id !== $driver->id) {
            return $this->unauthorizedResponse('This order is not assigned to you.');
        }

        $newStatus = $data['status'];

        if (!in_array($newStatus, OrderStatusEnum::values())) {
            return $this->validationErrorResponse(['status' => ['Invalid status value.']]);
        }

        try {
            switch ($newStatus) {
                case OrderStatusEnum::ON_WAY->value:
                    $this->validateOnWayTransition($order, $driver);
                    break;

                case OrderStatusEnum::DELIVERED->value:
                    $this->validateDeliveredTransition($order);
                    break;

                default:
                    throw new \Exception('Invalid status transition for drivers.');
            }

            $order->update(['status' => $newStatus]);

            if ($newStatus === OrderStatusEnum::ON_WAY->value) {
                $driver->update(['has_order' => true]);
            } elseif ($newStatus === OrderStatusEnum::DELIVERED->value) {
                $driver->update(['has_order' => false]);
            }

            OrderActivity::create([
                'order_id' => $order->id,
                'activity' => "Order status changed to {$newStatus} by driver {$driver->name}",
                'activity_date' => now(),
                'order_status' => $newStatus,
            ]);

            return $this->successResponse($order, 'Order status updated successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function getActivities(Request $request,$orderId)
    {
        $driver = $request->user();
        $order = Order::where('id', $orderId)
            ->where('driver_id', $driver->id)
            ->first();

        if (!$order) {
            return $this->notFoundResponse('Order not found or not assigned to you.');
        }

        $activities = $order->activity;

        return $this->successResponse($activities, 'Order activities retrieved successfully.');
    }

    private function validateOnWayTransition(Order $order, Driver $driver): void
    {
        if ($order->status !== OrderStatusEnum::SHIPPED->value) {
            throw new \Exception('Cannot start delivery unless the order is shipped.');
        }

        if ($driver->has_order) {
            throw new \Exception('You already have an active order. Please complete it first.');
        }
    }

    private function validateDeliveredTransition(Order $order): void
    {
        if ($order->status !== OrderStatusEnum::ON_WAY->value) {
            throw new \Exception('Cannot mark the order as delivered unless it is on the way.');
        }

        if ($order->status === OrderStatusEnum::DELIVERED->value) {
            throw new \Exception('This order has already been delivered.');
        }
    }

}