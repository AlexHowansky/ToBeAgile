<?php

namespace ToBeAgile;

class User
{
    protected $firstName;

    protected $lastName;

    protected $userEmail;

    protected $userName;

    protected $password;
    
    protected $loggedIn = false;
    
    protected $isSeller = false;

    public function __construct(
        string $firstName,
        string $lastName,
        string $userEmail,
        string $userName,
        string $password
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->userEmail = $userEmail;
        $this->userName = $userName;
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
    
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }
    
    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }
    
    public function login()
    {
        $this->loggedIn = true;
    }
    
    public function logout()
    {
        $this->loggedIn = false;
    }
    
    public function isSeller(): bool
    {
        return $this->isSeller;
    }
    
    public function setSeller(bool $isSeller)
    {
        $this->isSeller = $isSeller;
    }

}
