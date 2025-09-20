<?php

declare(strict_types=1);

namespace app\modules\users\enums;

use Yii;

enum UserStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => Yii::t('users.enum', 'user.active'),
            self::INACTIVE => Yii::t('users.enum', 'user.inactive'),
        };
    }

    public static function labels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->label();
        }
        return $labels;
    }

    public static function fromValue(int $value): self
    {
        return match($value) {
            0 => self::INACTIVE,
            1 => self::ACTIVE,
            default => throw new \ValueError("Invalid user status value: $value"),
        };
    }
}
