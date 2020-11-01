<?php

namespace ToBeAgileTest;

class UserTest extends \PHPUnit\Framework\TestCase
{
    
    protected $user = null;
    
    public function setUp(): void
    {
        $firstName = 'Groverly';
        $lastName = 'Spielmann';
        $userEmail = 'grover@sesame.com';
        $userName = 'SuperGrover';
        $password = 'DerthNader';
        $this->user = new \ToBeAgile\User($firstName, $lastName, $userEmail, $userName, $password);
    }

    public function testCreateUser()
    {
        $this->assertSame('Groverly', $this->user->getFirstName());
        $this->assertSame('Spielmann', $this->user->getLastName());
        $this->assertSame('grover@sesame.com', $this->user->getUserEmail());
        $this->assertSame('SuperGrover', $this->user->getUserName());
        $this->assertSame('DerthNader', $this->user->getPassword());
    }
    
    public function testSeller()
    {
        $this->assertFalse($this->user->isSeller());
        $this->user->setSeller(true);
        $this->assertTrue($this->user->isSeller());
        $this->user->setSeller(false);
        $this->assertFalse($this->user->isSeller());
    }
    
    public function testPreferredSellerNotSeller()
    {
        $this->expectException(\Exception::class);
        $this->user->setSeller(false);
        $this->user->setPreferredSeller(true);
    }
    
    public function testPreferredSeller()
    {
        $this->user->setSeller(true);
        $this->assertFalse($this->user->isPreferredSeller());
        $this->user->setPreferredSeller(true);
        $this->assertTrue($this->user->isPreferredSeller());
        $this->user->setPreferredSeller(false);
        $this->assertFalse($this->user->isPreferredSeller());

    }

}
