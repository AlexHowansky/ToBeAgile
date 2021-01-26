<?php

namespace ToBeAgileTest;

class UsersTest extends \PHPUnit\Framework\TestCase
{

    protected $users;

    public function setUp(): void
    {
        $this->users = new \ToBeAgile\Users();

        $firstName = 'Groverly';
        $lastName = 'Spielmann';
        $userEmail = 'grover@sesame.com';
        $userName = 'SuperGrover';
        $password = 'DerthNader';
        $this->users->register($firstName, $lastName, $userEmail, $userName, $password);

        $firstName = 'Big';
        $lastName = 'Bird';
        $userEmail = 'bigbird@sesame.com';
        $userName = 'BigBird';
        $password = 'IamYellow';
        $this->users->register($firstName, $lastName, $userEmail, $userName, $password);
    }

    public function testCantCreateDuplicateUser()
    {
        $this->expectException(\Exception::class);
        $firstName = 'Big';
        $lastName = 'Bird';
        $userEmail = 'bigbird@sesame.com';
        $userName = 'BigBird';
        $password = 'IamYellow';
        $this->users->register($firstName, $lastName, $userEmail, $userName, $password);
    }

    public function testFindByUserName()
    {
        $userName = 'BigBird';
        $user = $this->users->findByUserName($userName);
        $this->assertInstanceOf('\ToBeAgile\User', $user);
        $this->assertSame($userName, $user->getUserName());
    }

    public function testLogin()
    {
        $userName = 'BigBird';
        $password = 'IamYellow';
        $user = $this->users->findByUserName($userName);
        $this->assertFalse($user->isLoggedIn());
        $user = $this->users->login($userName, $password);
        $this->assertInstanceOf('\ToBeAgile\User', $user);
        $this->assertTrue($user->isLoggedIn());
        $user->logout();
        $this->assertFalse($user->isLoggedIn());
    }

    public function testLoginBadPassword()
    {
        $this->expectException(\Exception::class);
        $userName = 'BigBird';
        $password = 'IamRed';
        $this->users->login($userName, $password);
    }

    public function testLoginNoUser()
    {
        $this->expectException(\Exception::class);
        $userName = 'Oscar';
        $password = 'Grouch';
        $this->users->login($userName, $password);
    }

    public function testUserNotFound()
    {
        $this->expectException(\Exception::class);
        $userName = 'Oscar';
        $this->users->findByUserName($userName);
        $this->assertTrue(false);
    }

}
