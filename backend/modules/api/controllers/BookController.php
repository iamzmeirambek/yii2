<?php
namespace backend\modules\api\controllers;

use backend\modules\api\services\BookService;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\data\ActiveDataProvider;
use Yii;
use common\models\Book;

class BookController extends ActiveController
{
    public $modelClass = Book::class;

    private BookService $service;

    public function __construct($id, $module, BookService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => ['application/json' => \yii\web\Response::FORMAT_JSON],
        ];

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['index', 'view'],
        ];

        return $behaviors;
    }

    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete']);

        $actions['index']['prepareDataProvider'] = fn() =>
        new ActiveDataProvider([
            'query' => Book::find(),
            'pagination' => ['pageSize' => 10],
        ]);

        return $actions;
    }

    public function actionCreate(): Book|array
    {
        try {
            $model = $this->service->create(Yii::$app->request->getBodyParams());
            Yii::$app->response->statusCode = 201;
            return $model;
        } catch (\DomainException $e) {
            Yii::$app->response->statusCode = 422;
            return ['error' => $e->getMessage()];
        }
    }

    public function actionUpdate($id): Book|array
    {
        try {
            return $this->service->update($id, Yii::$app->request->getBodyParams());
        } catch (\DomainException $e) {
            Yii::$app->response->statusCode = 422;
            return ['error' => $e->getMessage()];
        }
    }

    public function actionDelete($id): ?array
    {
        try {
            $this->service->delete($id);
            Yii::$app->response->statusCode = 204;
            return null;
        } catch (\Throwable $e) {
            Yii::$app->response->statusCode = 422;
            return ['error' => $e->getMessage()];
        }
    }
}
