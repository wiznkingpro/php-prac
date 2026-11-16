<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Model\User;

class UserAuthTest extends TestCase
{
    public function testUserPasswordHashingOnCreation(): void
    {
        $user = User::create([
            'login' => 'testuser_' . uniqid(),
            'password' => 'secret123',
            'first_name' => 'Тест',
            'last_name' => 'Тестов',
            'role' => 'user',
            'status' => 'active'
        ]);

        $this->assertEquals(md5('secret123'), $user->password);
    }

    public function testAttemptIdentitySuccessAndFail(): void
    {
        $login = 'admin_' . uniqid();
        $user = User::create([
            'login' => $login,
            'password' => 'pass123',
            'first_name' => 'Админ',
            'last_name' => 'Админов',
            'role' => 'admin',
            'status' => 'active'
        ]);

        $found = (new User())->attemptIdentity([
            'login' => $login,
            'password' => 'pass123'
        ]);
        $this->assertNotNull($found);
        $this->assertEquals($user->users_id, $found->users_id);

        $wrong = (new User())->attemptIdentity([
            'login' => $login,
            'password' => 'wrongpass'
        ]);
        $this->assertNull($wrong);
    }

    public function testRoleAssignment(): void
    {
        $user = User::create([
            'login' => 'sysadmin_' . uniqid(),
            'password' => 'pass',
            'role' => 'admin',
            'status' => 'active'
        ]);

        $this->assertEquals('admin', $user->role);

        $user->role = 'super_admin';
        $user->save();

        $user->refresh();
        $this->assertEquals('super_admin', $user->role);
    }
}
