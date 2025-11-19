<?php

namespace backend\modules\api\controllers;

use common\models\User;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;
use backend\modules\api\forms\UserRegisterForm;
use backend\modules\api\forms\UserLoginForm;
use backend\modules\api\services\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct($id, $module, UserService $userService, $config = [])
    {
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class,
            'only' => ['profile'],
        ];

        return $behaviors;
    }

    /**
     * POST /api/user/register
     */
    public function actionRegister(): array
    {
        $form = new UserRegisterForm();
        $form->load(Yii::$app->request->post(), '');

        if (!$form->validate()) {
            return ['errors' => $form->errors];
        }

        try {
            $user = $this->userService->register($form->attributes);
        } catch (\RuntimeException $e) {
            return ['error' => $e->getMessage()];
        }

        return [
            'message' => 'Registration successful',
            'user_id' => $user->id,
        ];
    }

    /**
     * POST /api/user/login
     * @throws UnauthorizedHttpException
     */
    public function actionLogin(): array
    {
        $form = new UserLoginForm();
        $form->load(Yii::$app->request->post(), '');

        if (!$form->validate()) {
            return ['errors' => $form->errors];
        }

        try {
            $user = $this->userService->login($form->attributes);
        } catch (\RuntimeException $e) {
            throw new UnauthorizedHttpException($e->getMessage());
        }

        return [
            'message' => 'Login successful',
            'token' => $user->auth_key,
            'user_id' => $user->id,
        ];
    }

    /**
     * GET /api/user/profile
     * Header: Authorization: Bearer <token>
     */
    public function actionProfile(): array
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;

        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => $user->status,
            'created_at' => $user->created_at,
        ];
    }
}
