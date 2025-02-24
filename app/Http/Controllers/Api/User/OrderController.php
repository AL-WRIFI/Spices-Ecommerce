<?php 

namespace App\Http\Controllers\Api\User;

use App\Enums\Order\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Actions\Order\AppointDriverAction;
use App\Actions\Order\OrderActivityAction;
use App\Actions\User\CreateOrderAction;
use App\Enums\Order\OrderActivityEnum;
use App\Http\Requests\AppointDriverRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\Order\OrderDetailsResource;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    use ApiResponseTrait;

    protected CreateOrderAction $createOrderAction;
    protected OrderActivityAction $orderActivityAction;

    public function __construct(CreateOrderAction $createOrderAction, OrderActivityAction $orderActivityAction)
    {
        $this->createOrderAction = $createOrderAction;
        $this->orderActivityAction = $orderActivityAction;

    }
    public function ongoingOrder(Request $request)
    {
        try {
            $user = $request->user();
            $orders = Order::where('user_id', $user->id)->whereIn('status', ['pending','processing','shipped','on_way'])->with('orderItems')->get();
            if (!$orders) {
                return $this->errorResponse(msg: 'Orders not found',code: 404);
            }
            return $this->successResponse(data:[OrderDetailsResource::collection($orders)], msg:'success');
        } catch (Exception $e) {
            return $this->errorResponse(msg:'failed', errors:$e->getMessage(), code:400);
        }
    }
    public function fetch(Request $request)
    {
        try {
            $user = User::find($request->user()->id);
            $orders = $user->orders;
            if (!$orders) {
                return $this->errorResponse(msg: 'Orders not found',code: 404);
            }
            return $this->successResponse(data:[OrderResource::collection($orders)], msg:'success');
        } catch (Exception $e) {
            return $this->errorResponse(msg:'failed', errors:$e->getMessage(), code:400);
        }
    }
    
    public function show(Request $request, $id)
    {
        
        try {
            $user = $request->user();
            $order = Order::where('id', $id)->where('user_id', $user->id)->with('orderItems')->first();
            if (!$order) {
                return $this->errorResponse(msg: 'Order not found', errors: 'No order found with the given ID', code: 404);
            }
            return $this->successResponse(data:[ new OrderDetailsResource($order)], msg:'success');
        } catch (Exception $e) {
            return $this->errorResponse(msg:'failed', errors:$e->getMessage(), code:400);
        }
    }

    public function store(OrderRequest $request): JsonResponse
    {
        try {
            $order = $this->createOrderAction->handle($request->validated());
            $this->orderActivityAction->handle($order, OrderActivityEnum::ORDER_PLACED);

            return $this->successResponse(data:[ new OrderDetailsResource($order)], msg:'Order created successfully.', code:200);

        } catch (Exception $e) {
            
            return $this->errorResponse(msg:'Order creation failed.', errors:$e->getMessage(), code:400);
        }
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where(['id' => $id,'user_id' => $request->user()->id, 'status' => OrderStatusEnum::PENDING->value])->update([
            'status' => OrderStatusEnum::CANCELLED->value
        ]);

        if (!$order) {
            return $this->errorResponse(msg: 'Order not found', errors: 'No order found with the given ID', code: 404);
        }

        return $this->successResponse(msg:'Order canceled successfully', code: 200);
    }
}