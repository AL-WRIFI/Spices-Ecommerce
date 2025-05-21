<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();
        $orders = Order::all();
        $products = Product::where('status', 1)->get(); // Only active products

        if ($orders->isEmpty()) {
            $this->command->warn('No orders found. Cannot seed order items.');
            return;
        }
        if ($products->isEmpty()) {
            $this->command->warn('No active products found. Cannot seed order items.');
            return;
        }

        $this->command->info('Starting to seed order items...');
        $totalItemsSeeded = 0;

        foreach ($orders as $order) {
            $numberOfItemsInOrder = $faker->numberBetween(1, 4); // Each order will have 1 to 4 items
            $currentOrderSubtotal = 0;
            $productIdsInCurrentOrder = []; // To avoid adding the same product multiple times to one order

            for ($i = 0; $i < $numberOfItemsInOrder; $i++) {
                $product = $products->whereNotIn('id', $productIdsInCurrentOrder)->random(); // Get a product not already in this order

                if (!$product) { // If all available products are already in this order
                    continue;
                }
                $productIdsInCurrentOrder[] = $product->id;


                $quantity = $faker->numberBetween(1, 3);
                // Use sale_price if available and less than original price, otherwise use price
                $priceAtOrder = ($product->sale_price && $product->sale_price < $product->price) ? $product->sale_price : $product->price;

                // The 'total_price' in the model is an accessor, so we don't need to fill it here.
                // The database schema for order_items should not have a 'total_price' column if it's purely an accessor.
                // If your schema *does* have a total_price column, you can calculate and set it:
                // $itemTotalPrice = $priceAtOrder * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,       // Store product name at the time of order
                    'product_price' => $priceAtOrder, // Store product price at the time of order
                    'quantity' => $quantity,
                    // 'total_price' => $itemTotalPrice, // Uncomment if you have this column in DB
                ]);
                $currentOrderSubtotal += ($priceAtOrder * $quantity);
                $totalItemsSeeded++;
            }

            // Update the order's subtotal and total_amount
            // The accessors in the Order model will handle discount and delivery if they are already set.
            $order->subtotal = $currentOrderSubtotal;
            $order->total_amount = ($currentOrderSubtotal - $order->discount_amount) + $order->delivery_amount;
            $order->saveQuietly(); // Use saveQuietly to avoid firing events if not needed during seeding

            $this->command->info("Order ID {$order->id}: {$numberOfItemsInOrder} items added. New subtotal: {$order->subtotal}, New total: {$order->total_amount}");
        }
        $this->command->info("Total of {$totalItemsSeeded} order items seeded successfully for existing orders.");
    }
}