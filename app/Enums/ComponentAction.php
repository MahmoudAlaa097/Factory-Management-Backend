<?php

namespace App\Enums;

enum ComponentAction: string
{
    case Cleaned   = 'cleaned';
    case Adjusted  = 'adjusted';
    case Repaired  = 'repaired';
    case Replaced  = 'replaced';
    case Inspected = 'inspected';

    public function label(string $locale = 'en'): string
    {
        return match($locale) {
            'ar' => match($this) {
                self::Cleaned   => 'تنظيف',
                self::Adjusted  => 'ضبط',
                self::Repaired  => 'إصلاح',
                self::Replaced  => 'استبدال',
                self::Inspected => 'فحص',
            },
            default => match($this) {
                self::Cleaned   => 'Cleaned',
                self::Adjusted  => 'Adjusted',
                self::Repaired  => 'Repaired',
                self::Replaced  => 'Replaced',
                self::Inspected => 'Inspected',
            },
        };
    }

    public function is(self $action): bool
    {
        return $this === $action;
    }

    public function isReplacement(): bool
    {
        return $this === self::Replaced;
    }
}
