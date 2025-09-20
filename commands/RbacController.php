<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        
        $auth->removeAll();

        // User permissions
        $userIndex = $auth->createPermission('user.index');
        $userIndex->description = 'List users';
        $auth->add($userIndex);

        $userView = $auth->createPermission('user.view');
        $userView->description = 'View user details';
        $auth->add($userView);

        $userCreate = $auth->createPermission('user.create');
        $userCreate->description = 'Create user';
        $auth->add($userCreate);

        $userUpdate = $auth->createPermission('user.update');
        $userUpdate->description = 'Update user';
        $auth->add($userUpdate);

        $userDelete = $auth->createPermission('user.delete');
        $userDelete->description = 'Delete user';
        $auth->add($userDelete);

        // Product permissions
        $productIndex = $auth->createPermission('product.index');
        $productIndex->description = 'List products';
        $auth->add($productIndex);

        $productView = $auth->createPermission('product.view');
        $productView->description = 'View product details';
        $auth->add($productView);

        $productCreate = $auth->createPermission('product.create');
        $productCreate->description = 'Create product';
        $auth->add($productCreate);

        $productUpdate = $auth->createPermission('product.update');
        $productUpdate->description = 'Update product';
        $auth->add($productUpdate);

        $productDelete = $auth->createPermission('product.delete');
        $productDelete->description = 'Delete product';
        $auth->add($productDelete);

        // Category permissions
        $categoryIndex = $auth->createPermission('product.category.index');
        $categoryIndex->description = 'List categories';
        $auth->add($categoryIndex);

        $categoryView = $auth->createPermission('product.category.view');
        $categoryView->description = 'View category details';
        $auth->add($categoryView);

        $categoryCreate = $auth->createPermission('product.category.create');
        $categoryCreate->description = 'Create category';
        $auth->add($categoryCreate);

        $categoryUpdate = $auth->createPermission('product.category.update');
        $categoryUpdate->description = 'Update category';
        $auth->add($categoryUpdate);

        $categoryDelete = $auth->createPermission('product.category.delete');
        $categoryDelete->description = 'Delete category';
        $auth->add($categoryDelete);

        // Roles
        $user = $auth->createRole('user');
        $user->description = 'Regular user';
        $auth->add($user);
        $auth->addChild($user, $productIndex);
        $auth->addChild($user, $productView);
        $auth->addChild($user, $categoryIndex);
        $auth->addChild($user, $categoryView);

        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);
        
        // Add all permissions to admin
        $auth->addChild($admin, $userIndex);
        $auth->addChild($admin, $userView);
        $auth->addChild($admin, $userCreate);
        $auth->addChild($admin, $userUpdate);
        $auth->addChild($admin, $userDelete);
        
        $auth->addChild($admin, $productIndex);
        $auth->addChild($admin, $productView);
        $auth->addChild($admin, $productCreate);
        $auth->addChild($admin, $productUpdate);
        $auth->addChild($admin, $productDelete);
        
        $auth->addChild($admin, $categoryIndex);
        $auth->addChild($admin, $categoryView);
        $auth->addChild($admin, $categoryCreate);
        $auth->addChild($admin, $categoryUpdate);
        $auth->addChild($admin, $categoryDelete);

        $this->stdout("RBAC roles and permissions have been initialized.\n");
        return ExitCode::OK;
    }
}
