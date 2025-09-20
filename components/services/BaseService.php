<?php

declare(strict_types=1);

namespace app\components\services;

use Throwable;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use app\components\traits\CacheTrait;
use app\components\behaviors\AuditBehavior;

abstract class BaseService
{
    use CacheTrait;

    protected static string $modelClass;

    public function list(array $conditions = []): array
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = static::$modelClass;
        $query = $modelClass::find();
        
        if (!empty($conditions)) {
            $query->where($conditions);
        }
        
        return $query->all();
    }

    public function findById(int $id): ?ActiveRecord
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = static::$modelClass;

        return $modelClass::findOne($id);
    }

    /**
     * @throws Exception
     */
    public function create(ActiveRecord $model, array $data): bool
    {
        if ($model->load($data) && $model->validate()) {
            $this->beforeCreate($model);

            return $model->save();
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function update(ActiveRecord $model, array $data): bool
    {
        if ($model->load($data) && $model->validate()) {
            $this->beforeUpdate($model);

            return $model->save();
        }

        return false;
    }

    /**
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function delete(ActiveRecord $model): bool
    {
        /** @var AuditBehavior $auditBehavior */
        $auditBehavior = $model->getBehavior('audit');
        if ($auditBehavior) {
            $auditBehavior->softDelete();
        } else {
            $model->delete();
        }

        return true;
    }

    public function getDataProvider(array $conditions = [], int $pageSize = 20, array $with = []): ActiveDataProvider
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = static::$modelClass;

        $query = $modelClass::find();
        
        if (!empty($conditions)) {
            $query->where($conditions);
        }
        
        if (!empty($with)) {
            $query->with($with);
        }
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
    }

    protected function beforeCreate(ActiveRecord $model): void {}

    protected function beforeUpdate(ActiveRecord $model): void {}
}