<?php

declare(strict_types=1);

namespace app\modules\products\enums;

use Yii;

enum CategoryStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => Yii::t('products.enum', 'category.active'),
            self::INACTIVE => Yii::t('products.enum', 'category.inactive'),
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
            default => throw new \ValueError("Invalid category status value: $value"),
        };
    }
}
