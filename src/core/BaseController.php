<?php

namespace App\core;

require __DIR__ . '/../../vendor/autoload.php';

use App\core\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class BaseController
{
    private static $twig = null;

    protected function render($view, $data = [])
    {
        return View::view($view, $data);
    }

    protected function renderBack($view, $data = [])
    {
        return View::BackOfficeView($view, $data);
    }

    protected function renderTwig($template, $data = [])
    {
        if (self::$twig === null) {
            $loader = new FilesystemLoader(__DIR__ . '/../view');
            self::$twig = new Environment($loader);
        }
        
        return self::$twig->render($template, $data);
    }
}

?>
