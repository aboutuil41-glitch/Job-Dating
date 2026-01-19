<?php
namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
    public static function BackOfficeView(string $view, array $data = []): string
    {
        extract($data);

        $file = __DIR__ . "../../view/backoffice/{$view}.php";


        ob_start();
        require $file;
        return ob_get_clean();
    }
    private static ?Environment $twig = null;

    public static function renderTwig(string $template, array $data = []): void
    {
        if (self::$twig === null) {
            $loader = new FilesystemLoader('../view');

            self::$twig = new Environment($loader, [
                'cache' => false, // __DIR__ . '/../../storage/cache' in prod
                'debug' => true,
            ]);
        }

        echo self::$twig->render($template . '.twig', $data);
    }


        public static function renderTwigBack(string $template, array $data = []): void
    {
        if (self::$twig === null) {
            $loader = new FilesystemLoader(__DIR__.'/../view/backoffice');

            self::$twig = new Environment($loader, [
                'cache' => false, // __DIR__ . '/../../storage/cache' in prod
                'debug' => true,
            ]);
        }

        echo self::$twig->render($template . '.twig', $data);
    }
}
