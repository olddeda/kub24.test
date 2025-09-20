<?php

declare(strict_types=1);

namespace app\modules\users\services;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use app\modules\users\models\User;
use app\modules\users\enums\UserStatus;
use app\components\services\BaseService;

class UserService extends BaseService
{
    protected static string $modelClass = User::class;

    /**
     * @throws Exception
     */
    protected function beforeCreate(ActiveRecord $model): void
    {
        /** @var User $model */
        $model->setPassword($model->password_hash);
        $model->generateAuthKey();
        $model->setStatus(UserStatus::ACTIVE);
    }

    public function create(ActiveRecord $model, array $data): bool
    {
        /** @var User $model */
        $model->scenario = 'create';
        
        if (parent::create($model, $data)) {
            if (!$model->hasErrors()) {
                $role = $model->getRole();
                if (!empty($role)) {
                    $result = $model->assignRole($role);
                    \Yii::info("Role assignment result for user {$model->id}: " . ($result ? 'success' : 'failed') . ", role: {$role}", __METHOD__);
                } else {
                    \Yii::info("No role to assign for user {$model->id}", __METHOD__);
                }
            }
            return true;
        }
        
        return false;
    }

    public function update(ActiveRecord $model, array $data): bool
    {
        if (parent::update($model, $data)) {
            if (!$model->hasErrors()) {
                $role = $model->getRole();
                if (!empty($role)) {
                    $result = $model->assignRole($role);
                    \Yii::info("Role assignment result for user {$model->id}: " . ($result ? 'success' : 'failed') . ", role: {$role}", __METHOD__);
                } else {
                    \Yii::info("No role to assign for user {$model->id}", __METHOD__);
                }
            }
            return true;
        }
        
        return false;
    }

    /**
     * @throws Exception
     */
    protected function beforeUpdate(ActiveRecord $model): void
    {
        /** @var User $model */
        $oldPassword = $model->getOldAttribute('password_hash');
        
        if (!empty($model->password_hash) && $model->password_hash !== $oldPassword) {
            $model->setPassword($model->password_hash);
            $model->generateAuthKey();
        } else {
            $model->password_hash = $oldPassword;
        }
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function createAdmin(string $username, string $email, string $password): ?User
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->setStatus(UserStatus::ACTIVE);

        if ($user->save()) {
            $auth = Yii::$app->authManager;
            $adminRole = $auth->getRole('admin');
            if ($adminRole) {
                $auth->assign($adminRole, $user->getId());
            }
            
            return $user;
        }

        return null;
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function changePassword(string $username, string $newPassword): ?User
    {
        $user = User::findByUsername($username);
        if (!$user) {
            return null;
        }

        $user->setPassword($newPassword);
        $user->generateAuthKey();

        if ($user->save()) {
            return $user;
        }

        return null;
    }

    public static function getTotalCount(): int
    {
        return static::getCachedCount('users:total', static::$modelClass);
    }

    public static function getActiveCount(): int
    {
        return static::getCachedCount('users:active', static::$modelClass, ['status' => UserStatus::ACTIVE->value]);
    }

    public static function getInactiveCount(): int
    {
        return static::getCachedCount('users:inactive', static::$modelClass, ['status' => UserStatus::INACTIVE->value]);
    }
}
