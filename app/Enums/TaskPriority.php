<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;

enum TaskPriority: string implements HasColor
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public function label(string $locale = 'en'): string
    {
        return match($this) {
            self::LOW => $locale === 'ar' ? 'منخفض' : 'Low',
            self::MEDIUM => $locale === 'ar' ? 'متوسط' : 'Medium',
            self::HIGH => $locale === 'ar' ? 'عالي' : 'High',
            self::CRITICAL => $locale === 'ar' ? 'حرج' : 'Critical',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::LOW   => 'gray',
            self::HIGH => 'warning',
            self::MEDIUM  => 'info',
            self::CRITICAL   => 'danger',
        };
    }


    public static function options(string $locale = 'en'): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn($case) => $case->label($locale), self::cases())
        );
    }
}
