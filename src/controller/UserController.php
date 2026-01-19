<?php
namespace App\controller;

use PDO;

require __DIR__ . '/../../vendor/autoload.php';

use App\core\BaseController;
use App\models\user;



class UserController extends BaseController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO("sqlite::memory:");
    }


public function dashboard()
{
    $userModel = new User();
    $all = $userModel->loadAll();

    echo "<pre>";
    print_r($all);
    echo "</pre>";

    foreach ($all as $user) {
        echo "I'm " . $user['username'] . "<br>";
    }
}
    public function index()
{
    $userModel = new User();
    echo $this->renderBack('current_students', [
        'all' => $userModel->loadStudents()
    ]);
    
    
}
public function test($id){
    echo $this->renderBack('test', [
        'id' => $id
    ]);
}

public function showCreateForm()
{
    return $this->renderBack('create_student_form', []);
}

public function store()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /users/new');
        exit;
    }

    $user = new \App\models\user();
    $user->setName($_POST['name'] ?? '');
    $user->setEmail($_POST['email'] ?? '');
    $user->setPassword($_POST['password'] ?? '');
    $user->setRole('student');
    $user->setDate(date('Y-m-d H:i:s'));

    if ($user->create()) {
        header('Location: /StudentsIndex');
        exit;
    } else {
        header('Location: /users/new?error=1');
        exit;
    }
}
}