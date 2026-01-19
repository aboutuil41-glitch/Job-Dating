<?php
// config/bootstrap.php

// Base path of the project (wallet/)
define('BASE_PATH', dirname(__DIR__));

// Composer autoload
require BASE_PATH . '/vendor/autoload.php';

// Database + session config
require BASE_PATH . '/config/database.php';
?>