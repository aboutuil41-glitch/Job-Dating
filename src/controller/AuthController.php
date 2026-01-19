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
                ->required('username')
                ->min('username', 3)
                ->max('username', 20)
                ->unique('username', 'users', 'username')
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
        
        echo $this->renderRegisterForm($errors, $old);
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
                ->required('username')
                ->required('password');

            if ($validator->fails()) {
                Session::set('error', 'All fields are required.');
                header('Location: /login');
                exit;
            }

            if (Auth::attempt($_POST['username'], $_POST['password'])) {
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
        echo $this->renderLoginForm($error);
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

    private function renderLoginForm($error)
    {
        $errorHtml = $error ? "<div style='color: red;'>{$error}</div>" : '';
        $csrf = Security::csrfField();
        
        return "
        <h2>Login</h2>
        {$errorHtml}
        <form method='POST'>
            {$csrf}
            <input name='username' placeholder='Username or Email' required>
            <input type='password' name='password' placeholder='Password' required>
            <button>Login</button>
        </form>
        <p>Don't have an account? <a href='/register'>Register</a></p>
        ";
    }

    private function renderRegisterForm($errors, $old)
    {
        $usernameError = $errors['username'][0] ?? '';
        $emailError = $errors['email'][0] ?? '';
        $passwordError = $errors['password'][0] ?? '';
        
        $csrf = Security::csrfField();
        $username = Security::sanitize($old['username'] ?? '');
        $email = Security::sanitize($old['email'] ?? '');
        
        return "
        <h2>Register</h2>
        <form method='POST'>
            {$csrf}
            <div>
                <input name='username' placeholder='Username' value='{$username}' required>
                <span style='color: red;'>{$usernameError}</span>
            </div>
            <div>
                <input name='email' type='email' placeholder='Email' value='{$email}' required>
                <span style='color: red;'>{$emailError}</span>
            </div>
            <div>
                <input type='password' name='password' placeholder='Password' required>
                <span style='color: red;'>{$passwordError}</span>
            </div>
            <div>
                <textarea name='bio' placeholder='Bio (optional)'></textarea>
            </div>
            <button>Register</button>
        </form>
        <p>Already have an account? <a href='/login'>Login</a></p>
        ";
    }

    public function index()
    {
        return $this->render('create_user', [
            'name' => 'Ali',
            'LastName' => 'joe'
        ]);
    }
}