<?php

namespace ToBeAgileTest;

class UserTest extends \PHPUnit\Framework\TestCase
{
    
    public function testCreateUser()
    {
        $firstName = 'Groverly';
        $lastName = 'Spielmann';
        $userEmail = 'grover@sesame.com';
        $userName = 'SuperGrover';
        $password = 'DerthNader';
        $user = new \ToBeAgile\User($firstName, $lastName, $userEmail, $userName, $password);
        $this->assertSame($firstName, $user->getFirstName());
        $this->assertSame($lastName, $user->getLastName());
        $this->assertSame($userEmail, $user->getUserEmail());
        $this->assertSame($userName, $user->getUserName());
        $this->assertSame($password, $user->getPassword());
    }
        
}
