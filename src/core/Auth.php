<?php

namespace App\Core;

use App\models\user as User;

class Auth
{
    // 1. LOGIN METHOD - Main authentication logic
    public static function login(string $email, string $password): bool
    {
        $userModel = new User();
        $userData = $userModel->findByEmail($email);
        
        if (!$userData) {
            return false; // User not found
        }
        
        if ($userModel->verifyPassword($password, $userData['password'])) {
            Session::login($userData); // Use your new Session::login method
            return true;
        }
        
        return false; // Wrong password
    }

    // 2. REGISTER METHOD
    public static function register(array $data): bool
    {
        $userModel = new User();
        
        // Check if email already exists
        if ($userModel->findByEmail($data['email'])) {
            return false; // Email already exists
        }
        
        if ($userModel->createUser($data)) {
            // Auto-login after registration
            $userData = $userModel->findByEmail($data['email']);
            Session::login($userData);
            return true;
        }
        
        return false;
    }

    // 3. LOGOUT METHOD
    public static function logout(): void
    {
        Session::logout();
    }

    // 4. CHECK IF LOGGED IN
    public static function check(): bool
    {
        return Session::isLoggedIn();
    }

    // 5. GET CURRENT USER
    public static function user(): ?array
    {
        return Session::getCurrentUser();
    }

    // 6. REQUIRE LOGIN (redirect if not logged in)
    public static function requireAuth(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    // 7. REQUIRE GUEST (redirect if already logged in)
    public static function requireGuest(): void
    {
        if (self::check()) {
            header('Location: /dashboard');
            exit;
        }
    }
}