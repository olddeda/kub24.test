<?php

declare(strict_types=1);

namespace app\modules\products\services;

use yii\data\ActiveDataProvider;
use app\modules\products\models\Product;
use app\modules\products\enums\ProductStatus;
use app\components\services\BaseService;

class ProductService extends BaseService
{
    protected static string $modelClass = Product::class;
    
    public static function getTotalCount(): int
    {
        return static::getCachedCount('products:total', static::$modelClass);
    }

    public static function getActiveCount(): int
    {
        return static::getCachedCount('products:active', static::$modelClass, ['status' => ProductStatus::ACTIVE->value]);
    }

    public static function getInactiveCount(): int
    {
        return static::getCachedCount('products:inactive', static::$modelClass, ['status' => ProductStatus::INACTIVE->value]);
    }
}
