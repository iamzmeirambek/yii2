<?php

namespace backend\modules\api\forms;

use yii\base\Model;

class UserRegisterForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => \common\models\User::class, 'targetAttribute' => 'email', 'message' => 'Email уже зарегистрирован.'],
            ['username', 'unique', 'targetClass' => \common\models\User::class, 'targetAttribute' => 'username', 'message' => 'Имя пользователя уже занято.'],
            ['username', 'string', 'max' => 50],
            ['password', 'string', 'min' => 6],
        ];
    }
}
