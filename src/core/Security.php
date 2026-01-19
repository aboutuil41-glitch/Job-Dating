<?php

namespace App\Core;

class Security
{
    public static function generateCsrfToken()
    {
        if (!Session::has('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            Session::set('csrf_token', $token);
        }
        return Session::get('csrf_token');
    }

    public static function verifyCsrfToken($token)
    {
        $sessionToken = Session::get('csrf_token');
        return $sessionToken && hash_equals($sessionToken, $token);
    }

    public static function csrfField()
    {
        $token = self::generateCsrfToken();
        return "<input type='hidden' name='csrf_token' value='{$token}'>";
    }

    public static function sanitize($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}