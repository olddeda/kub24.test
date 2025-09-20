<?php

declare(strict_types=1);

namespace app\modules\products\controllers;

use app\modules\products\enums\ProductStatus;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use app\modules\products\models\Product;
use app\modules\products\services\ProductService;

class DefaultController extends Controller
{
    private readonly ProductService $service;

    public function __construct($id, $module, $config = []) {
        parent::__construct($id, $module, $config);

        $this->service = new ProductService();
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['product.index'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['product.view'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['product.create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['product.update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['product.delete'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index', [
            'dataProvider' => $this->service->getDataProvider(with: ['category']),
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->getModel($id),
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Product();
        $model->setStatus(ProductStatus::ACTIVE);

        if (Yii::$app->request->isPost && $this->service->create($model, Yii::$app->request->post())) {
            Yii::$app->session->setFlash('success', Yii::t('products', 'message.created'));

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->getModel($id);

        if (Yii::$app->request->isPost && $this->service->update($model, Yii::$app->request->post())) {
            Yii::$app->session->setFlash('success', Yii::t('products', 'message.updated'));

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->getModel($id);

        $this->service->delete($model);

        Yii::$app->session->setFlash('success', Yii::t('products', 'message.deleted'));

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): Product
    {
        /** @var Product $model */
        $model = $this->service->findById($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('products', 'error.not_found'));
        }

        return $model;
    }
}
