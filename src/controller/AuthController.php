<?php
namespace App\Controller;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Session;
use App\Core\Validator;

class AuthController extends BaseController
{
    // REGISTER
    public function register()
    {
        Auth::requireGuest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // CSRF check
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                Session::set('error', 'Invalid CSRF token');
                header('Location: /register');
                exit;
            }

            // Validation
            $validator = Validator::make($_POST)
                ->required('name')->min('name', 3)->max('name', 100)
                ->required('email')->email('email')->unique('email', 'users', 'email')
                ->required('password')->min('password', 6);

            if ($validator->fails()) {
                Session::set('errors', $validator->errors());
                Session::set('old', $_POST);
                header('Location: /register');
                exit;
            }

            // Create user
            $data = $_POST;
            $data['role'] = $data['role'] ?? 'student'; // default to student

            if (Auth::register($data)) {
                $user = Auth::user();
                $redirect = $user['role'] === 'admin' ? '/dashboard' : '/home';
                header("Location: {$redirect}");
                exit;
            } else {
                Session::set('error', 'Registration failed.');
                header('Location: /register');
                exit;
            }
        }

        // GET request -> render form
        $errors = Session::get('errors') ?? [];
        $old = Session::get('old') ?? [];
        $error = Session::get('error') ?? null;

        Session::remove('errors');
        Session::remove('old');
        Session::remove('error');

        echo $this->renderTwigAuth('register', [
            'title' => 'Inscription',
            'heading' => 'Inscription',
            'subtitle' => 'Créez votre compte YouCode',
            'action' => '/register',
            'csrf_token' => Security::csrfField(),
            'errors' => $errors,
            'old' => $old,
            'error' => $error
        ]);
    }

    // LOGIN
    public function login()
    {
        Auth::requireGuest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // CSRF check
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                Session::set('error', 'Invalid CSRF token');
                header('Location: /login');
                exit;
            }

            // Validation
            $validator = Validator::make($_POST)
                ->required('email')->email('email')
                ->required('password');

            if ($validator->fails()) {
                Session::set('error', 'All fields are required.');
                Session::set('old', $_POST);
                header('Location: /login');
                exit;
            }

            // Attempt login
            if (Auth::login($_POST['email'], $_POST['password'])) {
                $user = Auth::user();
                $redirect = $user['role'] === 'admin' ? '/dashboard' : '/home';
                header("Location: {$redirect}");
                exit;
            } else {
                Session::set('error', 'Invalid credentials.');
                Session::set('old', $_POST);
                header('Location: /login');
                exit;
            }
        }

        // GET request -> render form
        $error = Session::get('error');
        $old = Session::get('old') ?? [];

        Session::remove('error');
        Session::remove('old');

        echo $this->renderTwigAuth('login', [
            'title' => 'Connexion',
            'heading' => 'Connexion',
            'subtitle' => 'Accédez à votre espace sécurisé',
            'action' => '/login',
            'csrf_token' => Security::csrfField(),
            'error' => $error,
            'old' => $old
        ]);
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        header('Location: /login');
        exit;
    }

    // ADMIN DASHBOARD
    public function dashboard()
    {
        Auth::requireAuth();

        if (!Session::hasRole('admin')) {
            header('Location: /home');
            exit;
        }

        $user = Auth::user();
        echo "<h1>Admin Dashboard</h1>";
        echo "<p>Welcome admin, " . Security::sanitize($user['name']) . "</p>";
    }
}
