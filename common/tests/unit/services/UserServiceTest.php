<?php

namespace backend\tests\unit\services;

use backend\modules\api\services\UserService;
use common\models\User;
use Yii;
use Codeception\Test\Unit;

class UserServiceTest extends Unit
{
    private UserService $service;

    protected function _before(): void
    {
        $this->service = new UserService();

        // Очистка таблицы пользователей перед тестом
        User::deleteAll();
    }

    public function testRegisterSuccess()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'testuser@mail.com',
            'password' => '123456',
        ];

        $user = $this->service->register($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['username'], $user->username);
        $this->assertEquals($data['email'], $user->email);
        $this->assertNotEmpty($user->auth_key);
        $this->assertTrue($user->validatePassword($data['password']));
    }

    public function testRegisterFailsOnDuplicateEmail()
    {
        // Сначала создаём пользователя
        $existing = new User();
        $existing->username = 'existinguser';
        $existing->email = 'testuser@mail.com';
        $existing->setPassword('password');
        $existing->generateAuthKey();
        $existing->save();

        $this->expectException(\InvalidArgumentException::class);

        // Пытаемся зарегистрировать с тем же email
        $data = [
            'username' => 'newuser',
            'email' => 'testuser@mail.com',
            'password' => '123456',
        ];

        $this->service->register($data);
    }

    public function testRegisterFailsOnInvalidData()
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'username' => '', // пустое имя
            'email' => 'not-an-email', // некорректный email
            'password' => '',
        ];

        $this->service->register($data);
    }
}
