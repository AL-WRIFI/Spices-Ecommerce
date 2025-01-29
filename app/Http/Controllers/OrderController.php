<?php

namespace App\Http\Controllers;

use App\Actions\User\CreateOrderAction;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected CreateOrderAction $createOrderAction;

    public function __construct(CreateOrderAction $createOrderAction)
    {
        $this->createOrderAction = $createOrderAction;
    }

    public function store(OrderRequest $request): JsonResponse
    {
        try {
            $order = $this->createOrderAction->handle($request->validated());
            return response()->json([
                'message' => 'Order created successfully.',
                'data' => new OrderResource($order),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Order creation failed.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
