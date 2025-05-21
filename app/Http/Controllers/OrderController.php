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
        $drivers = Driver::all();
        $pendingPaymentCount = Order::where('payment_status', 'pending')->count();
        $completedOrdersCount = Order::where('status', 'completed')->count();
        $refundedOrdersCount = Order::where('status', 'refunded')->count();
        $failedOrdersCount = Order::where('status', 'failed')->count();

        return view('admin.orders.index', [
            'orders' => $orders,
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
        $appointed = $appointDriverAction->handle(data: $request->validated());
        if($appointed) {
            redirect()->route('orders.index')->with('success', 'Driver appointed successfully.');
        }

        return  redirect()->route('orders.index')->with('error', 'Driver appointed failed.');
    }

    public function changeStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', OrderStatusEnum::values())
        ]);

        try {
            $order = Order::findOrFail($order->id);
            $order->update(['status' => $validated['status']]);
            
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
