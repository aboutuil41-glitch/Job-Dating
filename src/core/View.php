<?php
namespace App\Core;

class View
{
    public static function view(string $view, array $data = []): string
    {
        extract($data);

        $file = __DIR__ . "../../view/{$view}.php";


        ob_start();
        require $file;
        return ob_get_clean();
    }
    public static function dashboard(string $view, array $data = []): string
    {
        extract($data);

        $file = __DIR__ . "../../view/backoffice/{$view}.php";


        ob_start();
        require $file;
        return ob_get_clean();
    }
}
