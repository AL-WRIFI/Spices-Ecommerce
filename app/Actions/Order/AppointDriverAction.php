<?php 

namespace App\Actions\Order;

use App\Models\Order;


class AppointDriverAction
{
    public function handle($data)
    {
        return Order::where('id', $data['order_id'])->update([
            'driver_id' => $data['driver_id'],
            "driver_appointed" => true,
        ]);
    }
}