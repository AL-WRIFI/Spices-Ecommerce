<?php 

namespace App\Enums\Order;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case FAILED = 'failed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function description(): string
    {
        return match ($this) {
            self::PENDING => 'الطلب قيد الانتظار',
            self::PROCESSING => 'الطلب قيد المعالجة',
            self::SHIPPED => 'تم شحن الطلب',
            self::DELIVERED => 'تم تسليم الطلب',
            self::CANCELLED => 'تم إلغاء الطلب',
            self::REFUNDED => 'تم استرداد المبلغ',
            self::FAILED => 'فشل الطلب',
        };
    }
}