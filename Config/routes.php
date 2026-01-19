<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controller\AuthController;
use App\Controller\UserController;

$router = Router::getRouter();

/* ---------- ROUTES ---------- */

$router->get('', fn() => 'Home');
$router->get('user/{name}/{id}', fn($name, $id) => 'Welcome ' .$name. ' Your ID is ' .$id);
$router->get('hello', fn() => 'Go Luv urself');

$router->get('user/create', function () {
    require __DIR__ . '/../src/View/create_user.php';
});

$router->post('user', function () {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
});


$router->get('StudentsIndex', [UserController::class, 'index']);
$router->get('AddStudents/new', [UserController::class, 'showCreateForm']);
$router->post('AddStudents/store', [UserController::class, 'store']);
$router->dispatch();
