<?php
namespace App\Auth;

class User implements \Framework\Auth\User
{
    public $id;
    public $username;
    public $email;
    public $password;

    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }


    /**
     * getRoles
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        return [];
    }
}
