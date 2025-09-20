<?php

namespace app\commands;

use Throwable;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\StaleObjectException;
use yii\helpers\BaseConsole;
use yii\helpers\Console;
use Faker\Factory;
use app\modules\users\models\User;
use app\modules\users\enums\UserStatus;
use app\modules\products\models\Product;
use app\modules\products\models\ProductCategory;
use app\modules\products\enums\ProductStatus;
use app\modules\products\enums\CategoryStatus;

class SeedController extends Controller
{
    /**
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function actionAll(): int
    {
        $this->stdout("Starting data seeding...\n", BaseConsole::FG_GREEN);
        
        $this->actionUsers();
        $this->actionCategories();
        $this->actionProducts();
        
        $this->stdout("Data seeding completed successfully!\n", BaseConsole::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function actionUsers($count = 10): int
    {
        $this->stdout("Seeding users...\n", BaseConsole::FG_YELLOW);
        
        $faker = Factory::create();
        $auth = Yii::$app->authManager;
        $userRole = $auth->getRole('user');
        
        for ($i = 0; $i < $count; $i++) {
            $user = new User();
            $user->username = $faker->unique()->userName;
            $user->email = $faker->unique()->safeEmail;
            $user->setPassword('password123');
            $user->generateAuthKey();
            $user->setStatus(UserStatus::ACTIVE);
            
            if ($user->save()) {
                if ($userRole) {
                    $auth->assign($userRole, $user->getId());
                }
                $this->stdout("Created user: {$user->username}\n");
            } else {
                $this->stderr("Failed to create user: " . json_encode($user->getErrors()) . "\n");
            }
        }
        
        $this->stdout("Users seeding completed.\n", BaseConsole::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionCategories($count = 5): int
    {
        $this->stdout("Seeding categories...\n", BaseConsole::FG_YELLOW);
        
        $faker = Factory::create();
        $categories = [
            'Electronics',
            'Clothing',
            'Books',
            'Home & Garden',
            'Sports & Outdoors',
            'Health & Beauty',
            'Toys & Games',
            'Automotive',
        ];
        
        foreach (array_slice($categories, 0, $count) as $categoryName) {
            $category = new ProductCategory();
            $category->name = $categoryName;
            $category->description = $faker->paragraph;
            $category->setStatus(CategoryStatus::ACTIVE);
            
            if ($category->save()) {
                $this->stdout("Created category: {$category->name}\n");
            } else {
                $this->stderr("Failed to create category: " . json_encode($category->getErrors()) . "\n");
            }
        }
        
        $this->stdout("Categories seeding completed.\n", BaseConsole::FG_GREEN);
        return ExitCode::OK;
    }

    public function actionProducts($count = 50): int
    {
        $this->stdout("Seeding products...\n", BaseConsole::FG_YELLOW);
        
        $faker = Factory::create();
        $categories = ProductCategory::find()->where(['status' => CategoryStatus::ACTIVE->value])->all();
        
        if (empty($categories)) {
            $this->stderr("No categories found. Please seed categories first.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }
        
        for ($i = 0; $i < $count; $i++) {
            $product = new Product();
            $product->name = $faker->words(3, true);
            $product->description = $faker->paragraph;
            $product->price = $faker->randomFloat(2, 10, 1000);
            $product->category_id = $faker->randomElement($categories)->id;
            $product->setStatus(ProductStatus::ACTIVE);
            
            if ($product->save()) {
                $this->stdout("Created product: {$product->name}\n");
            } else {
                $this->stderr("Failed to create product: " . json_encode($product->getErrors()) . "\n");
            }
        }
        
        $this->stdout("Products seeding completed.\n", BaseConsole::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionClear(): int
    {
        $this->stdout("Clearing all seeded data...\n", BaseConsole::FG_RED);
        
        Product::deleteAll();
        ProductCategory::deleteAll();
        
        $users = User::find()->where(['!=', 'username', 'admin'])->all();
        foreach ($users as $user) {
            $user->delete();
        }
        
        $this->stdout("All seeded data cleared.\n", BaseConsole::FG_GREEN);
        return ExitCode::OK;
    }
}
