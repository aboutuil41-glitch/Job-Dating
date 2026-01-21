<?php
namespace App\Controller;

require __DIR__ . '/../../vendor/autoload.php';

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\Validator;
use App\Core\Security;
use App\Core\Session;

class AuthController extends BaseController
{
    public function login()
    {
        Auth::requireGuest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                header('Location: /Job-Dating/public/login');
                exit;
            }

            if (Auth::login($_POST['email'], $_POST['password'])) {
                // Get user data to check role
                $user = Auth::user();
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /Job-Dating/public/dashboard');
                } else {
                    header('Location: /Job-Dating/public/home');
                }
                exit;
            } else {
                Session::set('error', 'Invalid credentials.');
                header('Location: /Job-Dating/public/login');
                exit;
            }
        }

        $error = Session::get('error');
        Session::remove('error');

        $this->renderTwig('auth/login', [
            'title' => 'Connexion',
            'action' => '/Job-Dating/public/login',
            'csrf_token' => Security::generateCsrfToken(),
            'error' => $error
        ]);
    }

    public function register()
    {
        Auth::requireGuest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                header('Location: /Job-Dating/public/register');
                exit;
            }

            if (Auth::register($_POST)) {
                // Get user data to check role
                $user = Auth::user();
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /Job-Dating/public/dashboard');
                } else {
                    header('Location: /Job-Dating/public/home');
                }
                exit;
            } else {
                Session::set('error', 'Registration failed.');
                header('Location: /Job-Dating/public/register');
                exit;
            }
        }

        $error = Session::get('error');
        $errors = Session::get('errors') ?? [];
        $old = Session::get('old') ?? [];
        Session::remove('error');
        Session::remove('errors');
        Session::remove('old');

        $this->renderTwig('auth/register', [
            'title' => 'Inscription',
            'action' => '/Job-Dating/public/register',
            'csrf_token' => Security::generateCsrfToken(),
            'error' => $error,
            'errors' => $errors,
            'old' => $old
        ]);
    }

    public function logout()
    {
        Auth::logout();
        header('Location: /Job-Dating/public/login');
        exit;
    }

    public function dashboard()
    {   
        Auth::requireAuth();

        $user = Auth::user();
        if($user['role'] === 'admin')
        {
            echo  "<h1>Admin Dashboard</h1>";
            echo "<p> Welcome admin , " . Security::sanitize($user['name']) . "!</p>";
        }
        else
        {
            header('Location: /home');
            exit;
        }
    }

}