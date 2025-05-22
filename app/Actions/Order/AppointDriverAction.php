<?php 

namespace App\Actions\Order;

use App\Models\Driver;
use App\Models\Order;


class AppointDriverAction
{
    public function handle($data)
    {
        $driver = Driver::find($data['driver_id']);
        if($driver->has_order == 1) {
            return false;
        }else {
            $order = Order::where('id', $data['order_id'])->update([
                'status' => 'processing',
                'driver_id' => $data['driver_id'],
                "driver_appointed" => 1,
            ]);
            
            $driver->update([
                'has_order' => 1,
            ]);
        }

        return true;
    }
}