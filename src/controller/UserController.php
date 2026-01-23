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
    echo $this->renderTwigBack('current_students', [
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
    return $this->renderTwigBack('create_student_form', []);
}

public function store()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /Job-Dating/public/AddStudents/new');
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
        header('Location: /AddStudents/new?error=1');
        exit;
    }
}

public function test2()
{
    $this->renderTwigBack('test', [
        'name' => 'Developer'
    ]);
}
public function showEditForm($id)
{
    $userModel = new User();
    return $this->renderTwigBack('edit_current', [

    'student' => $userModel->findById($id)

    ]);
}

public function update($id)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Students/Edit/$id");
        exit;
    }

    $user = new User();
    $user = $user->loadById($id);

    if (!$user) {
        header('Location: /StudentsIndex');
        exit;
    }

    $user->setName($_POST['name'] ?? '');

    if ($user->updateOnly(['name'])) {
        header('Location: /StudentsIndex');
        exit;
    }

    header("Location: /Students/Edit/$id?error=1");
    exit;
}

public function delete($id)
{
    $user = new User();
    $user = $user->loadById($id);

    if (!$user) {
        header('Location: /StudentsIndex?error=notfound');
        exit;
    }

    if ($user->delete()) {
        header('Location: /StudentsIndex?success=deleted');
        exit;
    }

    header('Location: /StudentsIndex?error=fail');
    exit;
}

public function test3()
{
    $userModel = new User();
    $student = $userModel->findById(10);
    
    echo "<pre>";
    print_r($student);
    echo "</pre>";
}

}