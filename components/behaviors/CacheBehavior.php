<?php

declare(strict_types=1);

namespace app\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;

class CacheBehavior extends Behavior
{
    public array $cacheKeys = [];
    public array $taggedKeys = [];
    public string $cacheComponent = 'cache';
    public $onInvalidate = null;

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'refreshCache',
            ActiveRecord::EVENT_AFTER_UPDATE => 'refreshCache',
            ActiveRecord::EVENT_AFTER_DELETE => 'refreshCache',
        ];
    }

    public function refreshCache(Event $event): void
    {
        $this->invalidateKeys();
        $this->invalidateTaggedCache();
        
        if ($this->onInvalidate) {
            call_user_func($this->onInvalidate, $event);
        }
    }

    private function invalidateKeys(): void
    {
        if (empty($this->cacheKeys)) {
            return;
        }
        
        $cache = Yii::$app->get($this->cacheComponent);
        
        foreach ($this->cacheKeys as $key) {
            $cache->delete($key);
        }
    }

    private function invalidateTaggedCache(): void
    {
        if (empty($this->taggedKeys)) {
            return;
        }

        $cache = Yii::$app->get($this->cacheComponent);
        
        foreach ($this->taggedKeys as $pattern) {
            if (method_exists($cache, 'deleteByPattern')) {
                $cache->deleteByPattern($pattern);
            } else {
                $this->deleteByTag($pattern);
            }
        }
    }

    private function deleteByTag(string $tag): void
    {
        $cache = Yii::$app->get($this->cacheComponent);
        
        $taggedKeys = $cache->get("tag:$tag");
        if (is_array($taggedKeys)) {
            foreach ($taggedKeys as $key) {
                $cache->delete($key);
            }
            $cache->delete("tag:$tag");
        }
    }

    public function tagCacheKey(string $key, string $tag): void
    {
        $cache = Yii::$app->get($this->cacheComponent);
        $taggedKeys = $cache->get("tag:$tag") ?: [];
        $taggedKeys[] = $key;
        $cache->set("tag:$tag", array_unique($taggedKeys));
    }
}
