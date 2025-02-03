<?php 

namespace App\Enums\Order;

enum OrderActivityEnum: string
{
    case ORDER_PLACED = 'order_placed';
    case PAYMENT_RECEIVED = 'payment_received';
    case ORDER_PROCESSING = 'order_processing';
    case ORDER_SHIPPED = 'order_shipped';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case ORDER_DELIVERED = 'order_delivered';
    case ORDER_CANCELLED = 'order_cancelled';
    case ORDER_REFUNDED = 'order_refunded';
    case ORDER_FAILED = 'order_failed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function description(): string
    {
        return match ($this) {
            self::ORDER_PLACED => 'تم تقديم الطلب',
            self::PAYMENT_RECEIVED => 'تم استلام الدفع',
            self::ORDER_PROCESSING => 'الطلب قيد المعالجة',
            self::ORDER_SHIPPED => 'تم شحن الطلب',
            self::OUT_FOR_DELIVERY => 'الطلب في الطريق للتسليم',
            self::ORDER_DELIVERED => 'تم تسليم الطلب',
            self::ORDER_CANCELLED => 'تم إلغاء الطلب',
            self::ORDER_REFUNDED => 'تم استرداد المبلغ',
            self::ORDER_FAILED => 'فشل الطلب',
        };
    }

    public function targetOrderStatus(): ?OrderStatusEnum
    {
        return match ($this) {
            self::ORDER_PLACED => OrderStatusEnum::PENDING,
            self::PAYMENT_RECEIVED => OrderStatusEnum::PROCESSING,
            self::ORDER_PROCESSING => OrderStatusEnum::PROCESSING,
            self::ORDER_SHIPPED => OrderStatusEnum::SHIPPED,
            self::OUT_FOR_DELIVERY => OrderStatusEnum::SHIPPED,
            self::ORDER_DELIVERED => OrderStatusEnum::DELIVERED,
            self::ORDER_CANCELLED => OrderStatusEnum::CANCELLED,
            self::ORDER_REFUNDED => OrderStatusEnum::REFUNDED,
            self::ORDER_FAILED => OrderStatusEnum::FAILED,
            default => null,
        };
    }
}