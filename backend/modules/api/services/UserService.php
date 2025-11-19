<?php

namespace backend\modules\api\services;

use common\models\User;
use Yii;
use yii\base\Exception;

class UserService
{
    /**
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function register(array $data): User
    {
        $user = new User();
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->setPassword($data['password']);
        $user->generateAuthKey();
        $user->verification_token = Yii::$app->security->generateRandomString() . '_' . time();

        if (!$user->save()) {
            throw new \RuntimeException(json_encode($user->errors));
        }

        return $user;
    }

    public function login(array $data): User
    {
        $user = User::findOne(['username' => $data['username']]);

        if (!$user || !$user->validatePassword($data['password'])) {
            throw new \RuntimeException('Неверный логин или пароль');
        }

        return $user;
    }
}
