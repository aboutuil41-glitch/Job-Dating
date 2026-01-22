<?php
namespace App\Core;

use App\Models\User;

class Auth
{
    public static function login(string $email, string $password): bool
    {
        $userModel = new User();
        $userData = $userModel->findByEmail($email);

        if (!$userData) return false;

        if ($userModel->verifyPassword($password, $userData['password'])) {
            Session::login($userData);
            return true;
        }

        return false;
    }

    public static function register(array $data): bool
    {
        $userModel = new User();
        $data['role'] = $data['role'] ?? 'student';

        if ($userModel->findByEmail($data['email'])) return false;

        if ($userModel->createUser($data)) {
            $userData = $userModel->findByEmail($data['email']);
            Session::login($userData);
            return true;
        }

        return false;
    }

    public static function logout(): void
    {
        Session::logout();
    }

    public static function check(): bool
    {
        return Session::isLoggedIn();
    }

    public static function user(): ?array
    {
        return Session::getCurrentUser();
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireGuest(): void
    {
        if (self::check()) {
            $user = self::user();
            $redirect = $user['role'] === 'admin' ? '/dashboard' : '/home';
            header('Location: ' . $redirect);
            exit;
        }
    }
}
