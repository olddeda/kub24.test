<?php

namespace app\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
use yii\db\Expression;

/**
 * @property-read ActiveRecord $owner
 */
class AuditBehavior extends Behavior
{
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
            'blamable' => [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by'],
                ],
                'defaultValue' => null,
            ],
        ];
    }

    public function beforeInsert(): void
    {
        if (Yii::$app->has('user') && !Yii::$app->user->isGuest) {
            $this->owner->created_by = Yii::$app->user->id;
            $this->owner->updated_by = Yii::$app->user->id;
        }
    }

    public function beforeUpdate(): void
    {
        if (Yii::$app->has('user') && !Yii::$app->user->isGuest) {
            $this->owner->updated_by = Yii::$app->user->id;
        }
    }

    /**
     * @throws Exception
     */
    public function softDelete(): bool
    {
        $this->owner->deleted_at = new Expression('CURRENT_TIMESTAMP');
        if (Yii::$app->has('user') && !Yii::$app->user->isGuest) {
            $this->owner->deleted_by = Yii::$app->user->id;
        }
        return $this->owner->save(false);
    }
}
