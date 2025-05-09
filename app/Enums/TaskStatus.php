<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;

enum TaskStatus: string implements HasColor
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Review = 'review';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending   => 'gray',
            self::Review => 'warning',
            self::InProgress  => 'info',
            self::Completed   => 'success',
            self::Cancelled   => 'danger',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($status) => [$status->value => $status->label()])
            ->toArray();
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::InProgress => __('In Progress'),
            self::Review => __('In Review'),
            self::Completed => __('Completed'),
            self::Cancelled => __('Cancelled'),
        };
    }
}
