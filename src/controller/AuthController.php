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
    public function register()
    {
        Auth::requireGuest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                header('Location: /register');
                exit;
            }

            $validator = Validator::make($_POST)
                ->required('name')
                ->min('name', 3)
                ->max('name', 100)
                ->required('email')
                ->email('email')
                ->unique('email', 'users', 'email')
                ->required('password')
                ->min('password', 6);

            if ($validator->fails()) {
                Session::set('errors', $validator->errors());
                Session::set('old', $_POST);
                header('Location: /register');
                exit;
            }

            if (Auth::register($_POST)) {
                header('Location: /dashboard');
                exit;
            } else {
                header('Location: /register');
                exit;
            }
        }

        $errors = Session::get('errors') ?? [];
        $old = Session::get('old') ?? [];
        Session::remove('errors');
        Session::remove('old');
        
        echo $this->renderTwig('auth/register.twig', [
            'title' => 'Inscription',
            'heading' => 'Inscription',
            'subtitle' => 'Créez votre compte YouCode',
            'action' => '/register',
            'csrf_token' => Security::generateCsrfToken(),
            'errors' => $errors,
            'old' => $old
        ]);
    }

    public function login()
    {
        Auth::requireGuest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                header('Location: /login');
                exit;
            }

            $validator = Validator::make($_POST)
                ->required('email')
                ->email('email')
                ->required('password');

            if ($validator->fails()) {
                Session::set('error', 'All fields are required.');
                header('Location: /login');
                exit;
            }

            if (Auth::login($_POST['email'], $_POST['password'])) {
                header('Location: /dashboard');
                exit;
            } else {
                Session::set('error', 'Invalid credentials.');
                header('Location: /login');
                exit;
            }
        }

        $error = Session::get('error');
        Session::remove('error');
        echo $this->renderTwig('auth/login.twig', [
            'title' => 'Connexion',
            'heading' => 'Connexion',
            'subtitle' => 'Accédez à votre espace sécurisé',
            'action' => '/login',
            'csrf_token' => Security::generateCsrfToken(),
            'error' => $error,
            'old' => []
        ]);
    }

    public function logout()
    {
        Auth::logout();
        header('Location: /login');
        exit;
    }

    public function dashboard()
    {   
        Auth::requireAuth();

        $user = Auth::user();
        echo "<h1>Dashboard</h1>";
        echo "<p>Welcome, " . Security::sanitize($user['username']) . "!</p>";
        echo "<a href='/logout'>Logout</a>";
    }



    public function index()
    {
        return $this->render('create_user', [
            'name' => 'Ali',
            'LastName' => 'joe'
        ]);
    }
}