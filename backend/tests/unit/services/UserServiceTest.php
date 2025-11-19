<?php

namespace backend\tests\unit\services;

use PHPUnit\Framework\TestCase;
use backend\modules\api\services\UserService;

class UserServiceTest extends TestCase
{
    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserService();
    }

    public function testRegisterSuccess()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'testuser@mail.com',
            'password' => 'password123',
        ];

        $user = $this->service->register($data);

        $this->assertEquals('testuser', $user->username);
    }
}
