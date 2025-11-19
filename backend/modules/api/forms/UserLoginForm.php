<?php

namespace backend\modules\api\forms;

use yii\base\Model;

class UserLoginForm extends Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'string', 'max' => 50],
            ['password', 'string', 'min' => 6],
        ];
    }
}
