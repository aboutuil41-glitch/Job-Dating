<?php
namespace App\Core;

class Session
{
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        session_unset();
        session_destroy();
    }
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        self::set('user_id', $user['id']);
        self::set('user_email', $user['email']);
        self::set('user_role',$user['role']);
        self::set('user_name', $user['name']);
        self::set('is_logged_in',true);
    }
    public static function isLoggedIn():bool
    {
        return self::has('is_logged_in') && self::get('is_logged_in');
    }
    public static function getCurrentUser(): ?array
    {
        if (!self::isLoggedIn())
        {
        return null;
        }
        return ['id' => self::get('user_id'),'email' => self::get('user_email'),'role' => self::get('user_role'), 'name' => self::get('user_name')];
    }
    public static function logout():void
    {
        self::remove('user_id');
        self::remove('user_email');
        self::remove('user_role');
        self::remove('user_name');
        self::remove('is_logged_in');
        session_regenerate_id(true);
    }
    public static function hasRole(string $role): bool
    {
        return self::isLoggedIn() && self::get('user_role') === $role;
    }
}