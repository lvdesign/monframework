<?php
namespace App\Auth;

use Framework\Auth;

class AuthTwigExtension extends \Twig\Extension\AbstractExtension
{
    
    
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('current_user', [$this->auth, 'getUser'])
        ];
    }
}
