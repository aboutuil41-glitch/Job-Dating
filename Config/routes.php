<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controller\AuthController;
use App\Controller\UserController;
use App\Controller\CompanyController;
use App\Controller\AdsController;
use App\Controller\DashboardController;
use App\Controller\FrontOfficeController;

$router = Router::getRouter();

/* ---------- ROUTES ---------- */

// Home & Dashboard
$router->get('', [AuthController::class, 'login']);
$router->get('dashboard', [DashboardController::class, 'index']);

// Old test routes
$router->get('user/{name}/{id}', fn($name, $id) => 'Welcome ' .$name. ' Your ID is ' .$id);
$router->get('hello', fn() => 'Go Luv urself');

// ========== STUDENTS ==========
$router->get('test', [UserController::class, 'test3']);

// Index
$router->get('StudentsIndex', [UserController::class, 'index']);

// Create
$router->get('AddStudents/new', [UserController::class, 'showCreateForm']);
$router->post('AddStudents/store', [UserController::class, 'store']);

// Update
$router->get('Current/Students/Edit/{id}', [UserController::class, 'showEditForm']);
$router->post('Current/Students/Update/{id}', [UserController::class, 'update']);

// Delete
$router->get('Current/Students/Delete/{id}', [UserController::class, 'delete']);

// ========== COMPANIES ==========
// Index
$router->get('CompanyIndex', [CompanyController::class, 'loadAll']);

// Create
$router->get('AddCompany/new', [CompanyController::class, 'showCreateForm']);
$router->post('AddCompany/store', [CompanyController::class, 'store']);

// Update
$router->get('Company/Edit/{id}', [CompanyController::class, 'showEditForm']);
$router->post('Company/Update/{id}', [CompanyController::class, 'update']);

// Delete
$router->get('Company/Delete/{id}', [CompanyController::class, 'delete']);

// ========== ADS ==========
// Index
$router->get('Ads', [AdsController::class, 'loadAll']);

// Create
$router->get('Ads/New', [AdsController::class, 'showCreateForm']);
$router->post('Ads/Store', [AdsController::class, 'store']);

// Update
$router->get('Ads/Edit/{id}', [AdsController::class, 'showEditForm']);
$router->post('Ads/Update/{id}', [AdsController::class, 'update']);

// Delete
$router->get('Ads/Delete/{id}', [AdsController::class, 'softDelete']);

// Archive
$router->get('Ads/Archive', [AdsController::class, 'showArchived']);

// restore
$router->get('/Ads/Restore/{id}', [AdsController::class, 'restore']);

//Hard Reset
$router->get('/Ads/HardDelete/{id}', [AdsController::class, 'delete']);

// ========== AUTH ==========
// Register
$router->get('register', [AuthController::class, 'register']);
$router->post('register', [AuthController::class, 'register']);

// Login
$router->get('login', [AuthController::class, 'login']);
$router->post('login', [AuthController::class, 'login']);

// Logout
$router->get('logout', [AuthController::class, 'logout']);

// Dashboard (admin)
$router->get('dashboard', [DashboardController::class, 'index']);

// Home (student)
$router->get('home',[FrontOfficeController::class,'home']); // You can replace with StudentController later
$router -> get('jobs',[FrontOfficeController::class,'jobs']);
$router -> get('jobs/search',[FrontOfficeController::class,'searchJobs']);
$router -> get('job/{id}',[FrontOfficeController::class,'jobDetails']);


$router->dispatch();