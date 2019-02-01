<?php

namespace ToBeAgile;

class User
{

    protected $firstName;

    protected $isPreferredSeller = false;

    protected $isSeller = false;

    protected $lastName;

    protected $loggedIn = false;

    protected $password;

    protected $userEmail;

    protected $userName;

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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }

    public function isPreferredSeller(): bool
    {
        return $this->isPreferredSeller;
    }

    public function isSeller(): bool
    {
        return $this->isSeller;
    }

    public function login()
    {
        $this->loggedIn = true;
    }

    public function logout()
    {
        $this->loggedIn = false;
    }

    public function setPreferredSeller(bool $isPreferredSeller)
    {
        if ($this->isSeller() === false) {
            throw new \Exception('Only sellers may be preferred sellers.');
        }
        $this->isPreferredSeller = $isPreferredSeller;
    }

    public function setSeller(bool $isSeller)
    {
        $this->isSeller = $isSeller;
    }

}
