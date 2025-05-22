<?php

namespace App\Http\Controllers;

use App\Actions\Order\AppointDriverAction;
use App\Actions\Order\OrderActivityAction;
use App\Actions\User\CreateOrderAction;
use App\Enums\Order\OrderActivityEnum;
use App\Enums\Order\OrderStatusEnum;
use App\Http\Requests\AppointDriverRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Driver;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected CreateOrderAction $createOrderAction;
    protected OrderActivityAction $orderActivityAction;

    public function __construct(CreateOrderAction $createOrderAction, OrderActivityAction $orderActivityAction)
    {
        $this->createOrderAction = $createOrderAction;
        $this->orderActivityAction = $orderActivityAction;

    }

    public function index()
    {
        $orders = Order::with(['user', 'driver', 'coupon'])->latest()->get();
        $drivers = Driver::where('has_order', 0)->get();
        $pendingPaymentCount = Order::where('payment_status', 'pending')->count();
        $completedOrdersCount = Order::where('status', 'completed')->count();
        $refundedOrdersCount = Order::where('status', 'refunded')->count();
        $failedOrdersCount = Order::where('status', 'failed')->count();

        return view('admin.orders.index', [
            'orders' => $orders->load('user', 'driver', 'coupon'),
            'drivers' => $drivers,
            'pendingPaymentCount' => $pendingPaymentCount,
            'completedOrdersCount' => $completedOrdersCount,
            'refundedOrdersCount' => $refundedOrdersCount,
            'failedOrdersCount' => $failedOrdersCount,
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'driver', 'coupon'])->where('id', $id)->first();
        return view('admin.orders.details', compact('order'));
    }

    public function appointDriver(AppointDriverRequest $request, AppointDriverAction $appointDriverAction)
    {
        $data = $request->validated();
        Log::info('appointDriver', [$request->validated()]);
        try{
            $driver = Driver::find($data['driver_id']);
            $order = Order::find($data['order_id']);
            if($driver->has_order == 1 || $order->driver_appointed == 1) {
                return  redirect()->route('orders.index')->with('error', 'driver_appointed or driver has order');
            }else {
                $order->update([
                    'status' => 'processing',
                    'driver_id' => $data['driver_id'],
                    "driver_appointed" => 1,
                ]);
                
                $driver->update([
                    'has_order' => 1,
                ]);
                return redirect()->route('orders.index')->with('success', 'Driver appointed successfully.');
            }
            // $appointed = $appointDriverAction->handle(data: $request->validated());
            // Log::info('appointDriver', [$appointed]);
            // if($appointed) {
            // }
            // return redirect()->route('orders.index')->with('success', 'Driver appointed successfully.');

        } catch (Exception $e) {
            Log::error("message", [$e->getMessage()]);
            return  redirect()->route('orders.index')->with('error', $e->getMessage());
        }
    }

    public function changeStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', OrderStatusEnum::values())
        ]);

        try {
            $order = Order::findOrFail($order->id);
            $order->update(['status' => $validated['status']]);
            if($validated['status'] == 'delivered' || $validated['status'] == 'completed' || $validated['status'] == 'refunded' || $validated['status'] == 'failed' || $validated['status'] == 'cancelled') {
                if($order->driver_id) {
                    $driver = Driver::find($order->driver_id);
                    if ($driver) {
                        $driver->update(['has_order' => 0]);
                    }
                    $order->update(['driver_appointed' => 0]);
                }
                // $order->update(['driver_appointed' => 0]);
            }
            
            return redirect()->back()
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'تم تحديث الحالة بنجاح'
                ]);
                
        } catch (Exception $e) {
            return redirect()->back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'حدث خطأ: ' . $e->getMessage()
                ]);
        }
    }
}
