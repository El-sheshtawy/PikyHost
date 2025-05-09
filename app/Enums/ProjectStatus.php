<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;

enum ProjectStatus: string implements HasColor
{
    case DRAFT = 'draft';
    case PLANNED = 'planned';
    case ACTIVE = 'active';
    case ON_HOLD = 'on_hold';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::DRAFT   => 'gray',
            self::PLANNED => 'gray',
            self::ACTIVE  => 'info',
            self::COMPLETED   => 'success',
            self::CANCELLED  => 'danger',
            self::ON_HOLD   => 'warning',
        };
    }

    public function label(string $locale = 'en'): string
    {
        return match($this) {
            self::DRAFT => $locale === 'ar' ? 'مسودة' : 'Draft',
            self::PLANNED => $locale === 'ar' ? 'مخطط' : 'Planned',
            self::ACTIVE => $locale === 'ar' ? 'نشط' : 'Active',
            self::ON_HOLD => $locale === 'ar' ? 'معلق' : 'On Hold',
            self::COMPLETED => $locale === 'ar' ? 'مكتمل' : 'Completed',
            self::CANCELLED => $locale === 'ar' ? 'ملغى' : 'Cancelled',
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
