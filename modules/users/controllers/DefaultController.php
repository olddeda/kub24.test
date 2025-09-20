<?php

declare(strict_types=1);

namespace app\modules\users\controllers;

use Throwable;

use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;

use app\modules\users\enums\UserStatus;
use app\modules\users\models\User;
use app\modules\users\services\UserService;

class DefaultController extends Controller
{
    private readonly UserService $service;

    public function __construct($id, $module, $config = []) {
        parent::__construct($id, $module, $config);

        $this->service = new UserService();
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
                        'roles' => ['user.index'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['user.view'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['user.create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['user.update'],
                    ],
                    [
                        'actions' => ['change-password'],
                        'allow' => true,
                        'roles' => ['user.update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['user.delete'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index', [
            'dataProvider' => $this->service->getDataProvider(),
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
        $model = new User();
        $model->scenario = 'create';
        $model->setStatus(UserStatus::ACTIVE);
        $model->setRole('user');

        if (Yii::$app->request->isPost && $this->service->create($model, Yii::$app->request->post())) {
            Yii::$app->session->setFlash('success', Yii::t('users', 'message.created'));

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
            Yii::$app->session->setFlash('success', Yii::t('users', 'message.updated'));

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

        // Prevent users from deleting their own account
        if ($model->id === Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', Yii::t('users', 'error.cannot_delete_self'));
            return $this->redirect(['view', 'id' => $id]);
        }

        $this->service->delete($model);

        Yii::$app->session->setFlash('success', Yii::t('users', 'message.deleted'));

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionChangePassword(int $id): Response
    {
        $model = $this->getModel($id);
        
        if (Yii::$app->request->isPost) {
            $newPassword = Yii::$app->request->post('User')['new_password'] ?? '';
            
            if (!empty($newPassword)) {
                $model->setPassword($newPassword);
                $model->generateAuthKey();
                
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'message.password_changed'));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('users', 'error.password_change_failed'));
                }
            } else {
                Yii::$app->session->setFlash('error', Yii::t('users', 'error.password_required'));
            }
        }
        
        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): User
    {
        /** @var User $model */
        $model = $this->service->findById($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('users', 'error.not_found'));
        }

        return $model;
    }
}
