<?php

declare(strict_types=1);

namespace app\modules\users\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;
use app\components\behaviors\AuditBehavior;
use app\components\behaviors\CacheBehavior;
use app\components\traits\CacheTrait;
use app\modules\users\enums\UserStatus;

/**
 * @property string|int $id
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property ?string $deleted_at
 * @property int $created_by
 * @property ?int $updated_by
 * @property ?int $deleted_by
 * @property string $role
 * @property-read null|string $authKey
 * @property-write string $password
 * @property-read array $roles
 * @property-read string $statusLabel
 */
class User extends ActiveRecord implements IdentityInterface
{
    private ?string $_role = null;

    public static function tableName(): string
    {
        return '{{%user}}';
    }

    public function behaviors(): array
    {
        return [
            'audit' => AuditBehavior::class,
            'refreshCache' => [
                'class' => CacheBehavior::class,
                'cacheKeys' => ['users:total', 'users:active', 'users:inactive'],
                'onInvalidate' => [CacheTrait::class, 'clearAllL1Cache'],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            ['status', 'default', 'value' => UserStatus::ACTIVE->value],
            ['status', 'in', 'range' => [UserStatus::ACTIVE->value, UserStatus::INACTIVE->value]],
            
            [['username', 'email'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            
            ['password_hash', 'required', 'on' => 'create'],
            ['password_hash', 'string', 'min' => 6, 'on' => 'create'],
            [['password_hash', 'auth_key'], 'string', 'max' => 255],
            [['password_reset_token'], 'string', 'max' => 255],
            [['password_reset_token'], 'unique'],
            
            ['role', 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('users', 'field.id'),
            'username' => Yii::t('users', 'field.username'),
            'email' => Yii::t('users', 'field.email'),
            'status' => Yii::t('users', 'field.status'),
            'password_hash' => Yii::t('users', 'field.password'),
            'created_at' => Yii::t('users', 'field.created_at'),
            'updated_at' => Yii::t('users', 'field.updated_at'),
            'created_by' => Yii::t('users', 'field.created_by'),
            'updated_by' => Yii::t('users', 'field.updated_by'),
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

    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne(['id' => $id, 'status' => UserStatus::ACTIVE->value]);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return null;
    }

    public static function findByUsername(string $username): ?static
    {
        return static::findOne(['username' => $username, 'status' => UserStatus::ACTIVE->value]);
    }

    public static function findByEmail(string $email): ?static
    {
        return static::findOne(['email' => $email, 'status' => UserStatus::ACTIVE->value]);
    }

    public function getId(): int|string|null
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @throws Exception
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    public static function findByPasswordResetToken(string $token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::find()
            ->andWhere(['status' => UserStatus::ACTIVE])
            ->andWhere(['password_reset_token' => $token])
            ->one();
    }

    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'] ?? 3600;
        return $timestamp + $expire >= time();
    }

    public function getStatusLabel(): string
    {
        return UserStatus::tryFrom($this->status)?->label() ?? 'Unknown';
    }

    public function setStatus(UserStatus $status): void
    {
        $this->status = $status->value;
    }

    public function getRole(): ?string
    {
        return $this->_role;
    }

    public function setRole(string $role): void
    {
        $this->_role = $role;
    }

    public function getRoles(): array
    {
        return Yii::$app->authManager->getRolesByUser($this->getId());
    }

    public function hasRole(string $roleName): bool
    {
        $roles = $this->getRoles();
        return isset($roles[$roleName]);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public static function getRolesList(): array
    {
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRoles();
        $result = [];

        foreach ($roles as $role) {
            $result[$role->name] = $role->description ?: $role->name;
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    public function assignRole(string $roleName): bool
    {
        $authManager = Yii::$app->authManager;
        
        $authManager->revokeAll($this->getId());
        
        $role = $authManager->getRole($roleName);
        if ($role) {
            $authManager->assign($role, $this->getId());
            return true;
        }
        
        return false;
    }

    /**
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    public function afterFind(): void
    {
        parent::afterFind();

        $roles = $this->getRoles();
        if (!empty($roles)) {
            $this->setRole(array_key_first($roles));
        }
    }
}
