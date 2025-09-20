<?php

declare(strict_types=1);

namespace app\modules\products\services;

use yii\data\ActiveDataProvider;
use app\modules\products\models\Product;
use app\modules\products\models\ProductCategory;
use app\modules\products\enums\CategoryStatus;
use app\components\services\BaseService;

class CategoryService extends BaseService
{
    protected static string $modelClass = ProductCategory::class;
    
    public static function getTotalCount(): int
    {
        return static::getCachedCount('categories:total', static::$modelClass);
    }

    public static function getActiveCount(): int
    {
        return static::getCachedCount('categories:active', static::$modelClass, ['status' => CategoryStatus::ACTIVE->value]);
    }

    public static function getInactiveCount(): int
    {
        return static::getCachedCount('categories:inactive', static::$modelClass, ['status' => CategoryStatus::INACTIVE->value]);
    }
}
