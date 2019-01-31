<?php

namespace ToBeAgileTest;

class UsersTest extends \PHPUnit\Framework\TestCase
{

    protected $users;

    public function setUp()
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

    public function testFindByUserName()
    {
        $userName = 'BigBird';
        $user = $this->users->findByUserName($userName);
        $this->assertInstanceOf('\ToBeAgile\User', $user);
        $this->assertSame($userName, $user->getUserName());
    }
    
    /**
     * @expectedException \Exception
     */
    public function testUserNotFound()
    {
        $userName = 'Oscar';
        $this->users->findByUserName($userName);
        $this->assertTrue(false);
    }

    /**
     * @expectedException \Exception
     */
    public function testLoginNoUser()
    {
        $userName = 'Oscar';
        $password = 'Grouch';
        $this->users->login($userName, $password);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testLoginBadPassword()
    {
        $userName = 'BigBird';
        $password = 'IamRed';
        $this->users->login($userName, $password);
    }

    public function testLogin()
    {
        $userName = 'BigBird';
        $password = 'IamYellow';
        $user = $this->users->findByUserName($userName);
        $this->assertFalse($user->isLoggedIn());
        $user = $this->users->login($userName, $password);
        $this->assertInstanceOf('\ToBeAgile\User',$user);
        $this->assertTrue($user->isLoggedIn());
        $user->logout();
        $this->assertFalse($user->isLoggedIn());
    }
    
    public function testIsSeller()
    {
        $userName = 'BigBird';
        $user = $this->users->findByUserName($userName);
        $this->assertFalse($user->isSeller());
        $user->setSeller(true);
        $this->assertTrue($user->isSeller());
        $user->setSeller(false);
        $this->assertFalse($user->isSeller());
    }
    
    /**
     * @expectedException \Exception
     */
    public function testCantCreateDuplicateUser()
    {
        $firstName = 'Big';
        $lastName = 'Bird';
        $userEmail = 'bigbird@sesame.com';
        $userName = 'BigBird';
        $password = 'IamYellow';
        $this->users->register($firstName, $lastName, $userEmail, $userName, $password);
    }

}
