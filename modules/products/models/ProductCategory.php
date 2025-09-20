<?php

declare(strict_types=1);

namespace app\modules\products\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use app\components\traits\CacheTrait;
use app\components\behaviors\AuditBehavior;
use app\components\behaviors\CacheBehavior;
use app\modules\products\enums\CategoryStatus;
use app\modules\users\models\User;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property ?string $deleted_at
 * @property int $created_by
 * @property ?int $updated_by
 * @property ?int $deleted_by
 * @property-read User $createdBy
 * @property-read ?User $updatedBy
 * @property-read ?User $deletedBy
 * @property-read string $statusLabel
 * @property-read Product[] $products
 */
class ProductCategory extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%product_category}}';
    }

    public function behaviors(): array
    {
        return [
            'audit' => AuditBehavior::class,
            'refreshCache' => [
                'class' => CacheBehavior::class,
                'cacheKeys' => ['categories:total', 'categories:active', 'categories:inactive'],
                'onInvalidate' => [CacheTrait::class, 'clearAllL1Cache'],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => [CategoryStatus::ACTIVE->value, CategoryStatus::INACTIVE->value]],
            [['status'], 'default', 'value' => CategoryStatus::ACTIVE->value],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('products.category', 'field.id'),
            'name' => Yii::t('products.category', 'field.name'),
            'description' => Yii::t('products.category', 'field.description'),
            'status' => Yii::t('products.category', 'field.status'),
            'created_at' => Yii::t('products.category', 'field.created_at'),
            'updated_at' => Yii::t('products.category', 'field.updated_at'),
            'created_by' => Yii::t('products.category', 'field.created_by'),
            'updated_by' => Yii::t('products.category', 'field.updated_by'),
        ];
    }

    public static function find(): ActiveQuery
    {
        return parent::find()->andWhere(['IS', 'deleted_at', null]);
    }

    public static function findWithDeleted(): ActiveQuery
    {
        return parent::find();
    }

    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getUpdatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function getStatusLabel(): string
    {
        return CategoryStatus::tryFrom($this->status)?->label() ?? 'Unknown';
    }

    public function setStatus(CategoryStatus $status): void
    {
        $this->status = $status->value;
    }

    public static function getCategoryList(): array
    {
        return self::find()
            ->select(['name', 'id'])
            ->andWhere(['status' => CategoryStatus::ACTIVE->value])
            ->indexBy('id')
            ->column();
    }
}
