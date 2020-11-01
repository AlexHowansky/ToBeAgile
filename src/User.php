<?php

namespace ToBeAgile;

class User
{

    protected string $firstName;

    protected bool $isPreferredSeller = false;

    protected bool $isSeller = false;

    protected string $lastName;

    protected bool $loggedIn = false;

    protected string $password;

    protected string $userEmail;

    protected string $userName;

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

    public function login(): void
    {
        $this->loggedIn = true;
    }

    public function logout(): void
    {
        $this->loggedIn = false;
    }

    public function setPreferredSeller(bool $isPreferredSeller): void
    {
        if ($this->isSeller() === false) {
            throw new \Exception('Only sellers may be preferred sellers.');
        }
        $this->isPreferredSeller = $isPreferredSeller;
    }

    public function setSeller(bool $isSeller): void
    {
        $this->isSeller = $isSeller;
    }

}
