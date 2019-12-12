<?php

namespace Tests\Unit\Entity\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function testRequset()
    {
        $user = User::register(
            $name = 'name',
            $email = 'email',
            $password = 'password'
        );

        $this->assertNotEmpty($user);

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->password);
        $this->assertNotEquals($password, $user->password);

        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());
        $this->assertFalse($user->isAdmin());
    }
/*
    public function testVerify()
    {
        $user = User::register();
    }*/
}
