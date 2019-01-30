<?php

namespace ToBeAgile;

class Users
{

    protected $users = [];

    public function register ($firstName, $lastName, $userEmail, $userName, $password): User
    {
        $user = new User($firstName, $lastName, $userEmail, $userName, $password);
        $this->users[] = $user;
        return $user;
    }
    
    public function findByUserName(string $userName): User
    {
        foreach ($this->users as $user) {
            if ($user->getUserName() === $userName) {
                return $user;
            }
        }
        throw new \Exception('User not found');
    }
    
    public function login(string $userName, string $password): User
    {
        $user = $this->findByUserName($userName);
        if ($user->getPassword() === $password) {
            $user->login();
            return $user;
        }
        throw new \Exception('Bad password.');
    }
}
