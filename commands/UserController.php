<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use app\modules\users\models\User;
use app\modules\users\enums\UserStatus;

class UserController extends Controller
{
    public function actionCreateAdmin($username = null, $email = null, $password = null)
    {
        if (!$username) {
            $username = $this->prompt('Enter username:');
        }
        
        if (!$email) {
            $email = $this->prompt('Enter email:');
        }
        
        if (!$password) {
            $password = $this->prompt('Enter password:', ['required' => true, 'pattern' => '/.{6,}/']);
        }

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
                $this->stdout("Admin user '{$username}' has been created successfully with admin role.\n", Console::FG_GREEN);
            } else {
                $this->stdout("Admin user '{$username}' has been created, but admin role not found. Run 'php yii rbac/init' first.\n", Console::FG_YELLOW);
            }
            return ExitCode::OK;
        } else {
            $this->stderr("Failed to create admin user.\n", Console::FG_RED);
            foreach ($user->getErrors() as $attribute => $errors) {
                foreach ($errors as $error) {
                    $this->stderr("$attribute: $error\n");
                }
            }
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    public function actionInfo($email = null)
    {
        if (!$email) {
            $email = $this->prompt('Enter email:');
        }

        $user = User::find()->where(['email' => $email])->one();
        
        if (!$user) {
            $this->stderr("User with email '{$email}' not found.\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $this->stdout("User Information:\n", Console::FG_GREEN);
        $this->stdout("ID: {$user->id}\n");
        $this->stdout("Username: {$user->username}\n");
        $this->stdout("Email: {$user->email}\n");
        $this->stdout("Status: {$user->status} (" . $user->getStatusLabel() . ")\n");
        $this->stdout("Created: {$user->created_at}\n");
        $this->stdout("Updated: {$user->updated_at}\n");
        
        $roles = $user->getRoles();
        if (!empty($roles)) {
            $this->stdout("Roles: " . implode(', ', array_keys($roles)) . "\n");
        } else {
            $this->stdout("Roles: none\n");
        }

        return ExitCode::OK;
    }

    public function actionChangePassword($username = null, $password = null)
    {
        if (!$username) {
            $username = $this->prompt('Enter username:');
        }

        if (!$password) {
            $password = $this->prompt('Enter new password:', ['required' => true, 'pattern' => '/.{6,}/']);
        }
        
        $userService = new UserService();
        $user = $userService->changePassword($username, $password);
        
        if ($user) {
            $this->stdout("Password for user '{$username}' has been changed successfully.\n", Console::FG_GREEN);
            return ExitCode::OK;
        } else {
            $this->stderr("User '{$username}' not found or failed to change password.\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    public function actionList()
    {
        $users = User::find()->all();
        
        $this->stdout(sprintf("%-5s %-20s %-30s %-10s %-10s\n", 'ID', 'Username', 'Email', 'Status', 'Roles'));
        $this->stdout(str_repeat('-', 80) . "\n");
        
        foreach ($users as $user) {
            $roles = array_keys($user->getRoles());
            $this->stdout(sprintf(
                "%-5s %-20s %-30s %-10s %-10s\n",
                $user->id,
                $user->username,
                $user->email,
                $user->getStatusLabel(),
                implode(', ', $roles)
            ));
        }
        
        return ExitCode::OK;
    }
}
