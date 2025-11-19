<?php
namespace backend\modules\api\controllers;

use yii\rest\Controller;
use yii\web\Response;

class DefaultController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    public function actionIndex(): array
    {
        return [
            'status' => 'success',
            'message' => 'Welcome to API default controller',
        ];
    }

    public function actionHello($name = 'Guest'): array
    {
        return [
            'status' => 'success',
            'message' => "Hello, $name!",
        ];
    }
}
