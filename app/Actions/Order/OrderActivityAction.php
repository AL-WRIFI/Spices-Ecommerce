<?php 

namespace App\Actions\Order;

use App\Enums\Order\OrderActivityEnum;
use App\Models\OrderActivity;


class OrderActivityAction
{
    public function handle($order, OrderActivityEnum $activity)
    {
        $updatedOrder = tap($order)->update([
            'status' => $activity->targetOrderStatus(),
        ]);

        $order->activity()->create([
            'activity' => $activity->value,
            'activity_date' => now(),
            'order_status' => $updatedOrder->status,
        ]);
    }
}