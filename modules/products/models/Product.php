<?php

declare(strict_types=1);

namespace app\modules\products\models;

use Yii;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

use app\components\behaviors\AuditBehavior;
use app\components\behaviors\CacheBehavior;
use app\components\traits\CacheTrait;
use app\modules\products\enums\ProductStatus;
use app\modules\users\models\User;

/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property double $price
 * @property string $created_at
 * @property string $updated_at
 * @property ?string $deleted_at
 * @property int $created_by
 * @property ?int $updated_by
 * @property ?int $deleted_by
 * @property-read string $formattedPrice
 * @property-read ProductCategory $category
 * @property-read User $createdBy
 * @property-read ?User $updatedBy
 * @property-read ?User $deletedBy
 * @property-read string $categoryName
 * @property-read string $statusLabel
 */
class Product extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%product}}';
    }

    public function behaviors(): array
    {
        return [
            'audit' => AuditBehavior::class,
            'refreshCache' => [
                'class' => CacheBehavior::class,
                'cacheKeys' => ['products:total', 'products:active', 'products:inactive'],
                'onInvalidate' => [CacheTrait::class, 'clearAllL1Cache'],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['name', 'category_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['price'], 'number', 'min' => 0],
            [['category_id'], 'integer'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => [ProductStatus::ACTIVE->value, ProductStatus::INACTIVE->value]],
            [['status'], 'default', 'value' => ProductStatus::ACTIVE->value],
            [['category_id'], 'exist', 'targetClass' => ProductCategory::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('products', 'field.id'),
            'name' => Yii::t('products', 'field.name'),
            'description' => Yii::t('products', 'field.description'),
            'price' => Yii::t('products', 'field.price'),
            'category_id' => Yii::t('products', 'field.category'),
            'status' => Yii::t('products', 'field.status'),
            'created_at' => Yii::t('products', 'field.created_at'),
            'updated_at' => Yii::t('products', 'field.updated_at'),
            'created_by' => Yii::t('products', 'field.created_by'),
            'updated_by' => Yii::t('products', 'field.updated_by'),
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

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(ProductCategory::class, ['id' => 'category_id']);
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
        return ProductStatus::tryFrom($this->status)?->label() ?? 'Unknown';
    }

    public function setStatus(ProductStatus $status): void
    {
        $this->status = $status->value;
    }

    public function getCategoryName(): string
    {
        return $this->category ? $this->category->name : '';
    }

    /**
     * @throws InvalidConfigException
     */
    public function getFormattedPrice(): string
    {
        return Yii::$app->formatter->asCurrency($this->price);
    }

    public static function getActiveProducts(): array
    {
        return static::find()
            ->where(['status' => ProductStatus::ACTIVE->value])
            ->with('category')
            ->all();
    }

    public static function getProductsByCategory(int $categoryId): array
    {
        return static::find()
            ->where(['category_id' => $categoryId, 'status' => ProductStatus::ACTIVE->value])
            ->all();
    }
}
