<?php

declare(strict_types=1);

namespace app\components\traits;

use Yii;
use app\components\cache\L1Cache;

trait CacheTrait
{
    protected static function getCachedValue(string $key, callable $callback): mixed
    {
        $classKey = static::class;
        
        $value = L1Cache::get($classKey, $key);
        if ($value !== null) {
            return $value;
        }
        
        $cache = Yii::$app->cache;
        $value = $cache->get($key);
        
        if ($value === false) {
            $value = $callback();
            
            $l2Duration = Yii::$app->params['cache.l2.duration'] ?? 300;
            $cache->set($key, $value, $l2Duration);
        }
        
        $l1Duration = Yii::$app->params['cache.l1.duration'] ?? 60;
        L1Cache::set($classKey, $key, $value, $l1Duration);
        
        return $value;
    }

    protected static function getCachedCount(string $key, string $modelClass, array $conditions = []): int
    {
        return static::getCachedValue($key, function() use ($modelClass, $conditions) {
            $query = $modelClass::find();
            
            if (!empty($conditions)) {
                $query->where($conditions);
            }
            
            return $query->count();
        });
    }

    protected static function invalidateCache(string $key): void
    {
        $classKey = static::class;
        
        L1Cache::delete($classKey, $key);
        Yii::$app->cache->delete($key);
    }

    protected static function invalidatePattern(string $pattern): void
    {
        $classKey = static::class;
        
        L1Cache::clear($classKey);
        
        $cache = Yii::$app->cache;
        if (method_exists($cache, 'deleteByPattern')) {
            $cache->deleteByPattern($pattern);
        }
    }

    public static function clearAllL1Cache(): void
    {
        $classKey = static::class;
        L1Cache::clear($classKey);
    }

}