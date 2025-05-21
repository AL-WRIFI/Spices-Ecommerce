<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\Driver;
// use App\Models\Coupon; // قم بإلغاء التعليق إذا كان لديك كوبونات
use App\Enums\Order\OrderStatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create('ar_SA'); // استخدام Faker باللغة العربية للمزيد من الواقعية في العناوين
        $users = User::where('status', 1)->get();
        $drivers = Driver::where('status', 1)->get();
        // $coupons = Coupon::where('status', 1)->get(); // قم بإلغاء التعليق

        if ($users->isEmpty()) {
            $this->command->error('No active users found. Cannot seed orders.');
            return;
        }
        if ($drivers->isEmpty()) {
            $this->command->warn('No active drivers found. Orders might not have drivers assigned.');
        }

        $paymentMethods = ['cash_on_delivery', 'credit_card', 'mada', 'apple_pay'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        $orderStatuses = array_column(OrderStatusEnum::cases(), 'value');

        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $driver = $drivers->isNotEmpty() ? $drivers->random() : null;
            // $coupon = $coupons->isNotEmpty() && $faker->boolean(30) ? $coupons->random() : null; // قم بإلغاء التعليق

            $subtotal = 0;
            $discountAmount = 0;
            // if ($coupon) {
            //     $tempSubtotalForDiscount = $faker->randomFloat(2, 50, 300);
            //     $discountAmount = $coupon->type == 'fixed' ? $coupon->amount : ($tempSubtotalForDiscount * ($coupon->amount / 100));
            //     $discountAmount = min($discountAmount, $tempSubtotalForDiscount);
            // }
            $deliveryAmount = $faker->randomElement([15.00, 20.00, 25.00, 0.00]);
            $totalAmount = 0;

            $status = $faker->randomElement($orderStatuses);
            $driverAppointed = $driver && in_array($status, [
                OrderStatusEnum::PROCESSING->value,
                OrderStatusEnum::SHIPPED->value,
                OrderStatusEnum::ON_WAY->value,
                OrderStatusEnum::DELIVERED->value
            ]);

            $createdAt = $faker->dateTimeBetween('-6 months', 'now');
            $updatedAt = $faker->dateTimeBetween($createdAt, 'now');

            // --- تعديل بناء عنوان الشحن ---
            $cityName = $faker->city; // Faker سيولد اسم مدينة
            $districtName = $faker->streetSuffix . " " . $faker->lastName; // محاولة لإنشاء اسم حي (قد تحتاج لتعديل حسب مخرجات Faker العربية)
                                                                        // أو استخدم قائمة أحياء محددة مسبقًا:
                                                                        // $districts = ['حي العليا', 'حي السليمانية', 'حي الملز', 'حي النخيل', 'حي الروضة'];
                                                                        // $districtName = $faker->randomElement($districts);
            $streetName = $faker->streetName;
            $buildingNumber = $faker->buildingNumber;
            $landmark = $faker->boolean(30) ? "بالقرب من " . $faker->company : null; // معلم قريب أحيانًا

            $shippingAddressString = "المدينة: {$cityName}، الحي: {$districtName}، شارع: {$streetName}، رقم المبنى/المنزل: {$buildingNumber}";
            if ($landmark) {
                $shippingAddressString .= "، {$landmark}";
            }
            if ($faker->boolean(20)) { // إضافة ملاحظات أحيانًا
                $shippingAddressString .= ". ملاحظات: " . $faker->sentence(4);
            }
            // يمكنك أيضًا تضمين اسم المستلم ورقم الهاتف في هذا النص إذا أردت
            // $shippingAddressString = "المستلم: {$user->name} ({$user->phone}) - " . $shippingAddressString;

            Order::create([
                'user_id' => $user->id,
                'driver_id' => $driverAppointed ? $driver->id : null,
                // 'coupon_id' => $coupon ? $coupon->id : null,
                // 'coupon' => $coupon ? json_encode(['code' => $coupon->code, 'amount' => $coupon->amount, 'type' => $coupon->type]) : null,
                'shipping_address' => $shippingAddressString, // استخدام النص المنسق
                'payment_method' => $faker->randomElement($paymentMethods),
                'payment_status' => ($status === OrderStatusEnum::DELIVERED->value && $faker->boolean(80)) ? 'paid' : $faker->randomElement($paymentStatuses),
                'status' => $status,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'delivery_amount' => $deliveryAmount,
                'total_amount' => $totalAmount,
                'driver_appointed' => $driverAppointed,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
        $this->command->info('20 orders seeded successfully with descriptive shipping addresses.');
        $this->call(OrderItemSeeder::class);
    }
}