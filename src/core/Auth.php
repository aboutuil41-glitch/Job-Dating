<?php

namespace App\Core;

use App\Models\User;
use PDO;

class Auth
{
    public static function attempt($username, $password)
    {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return false;
        }

        if (password_verify($password, $userData['password'])) {
            Session::set('user_id', $userData['id']);
            Session::set('username', $userData['username']);
            Session::set('email', $userData['email']);
            Session::set('logged_in', true);
            return true;
        }

        return false;
    }

    public static function logout()
    {
        Session::destroy();
    }

    public static function check()
    {
        return Session::get('logged_in') === true;
    }

    public static function user()
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id' => Session::get('user_id'),
            'username' => Session::get('username'),
            'email' => Session::get('email')
        ];
    }

    public static function requireAuth()
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireGuest()
    {
        if (self::check()) {
            header('Location: /dashboard');
            exit;
        }
    }

    public static function register($data)
    {
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']); 
        $user->setBio($data['bio'] ?? '');
        $user->setDate(date('Y-m-d H:i:s'));

        if ($user->create()) {
            Session::set('user_id', $user->getId());
            Session::set('username', $data['username']);
            Session::set('email', $data['email']);
            Session::set('logged_in', true);
            return true;
        }

        return false;
    }
}