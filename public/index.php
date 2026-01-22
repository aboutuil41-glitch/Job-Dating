<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php'; // composer autoload
require __DIR__ . '/../config/bootstrap.php';

use App\Core\Session;

// Start session
Session::start();

// Load routes
require __DIR__ . '/../config/routes.php';
