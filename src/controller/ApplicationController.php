<?php
namespace App\controller;

use PDO;

require __DIR__ . '/../../vendor/autoload.php';

use App\core\BaseController;
use App\models\Applications;



class ApplicationController extends BaseController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO("sqlite::memory:");
    }

    public function loadTheApplicantes(){
         $applicantes = new Applications();

        echo $this->renderTwigBack('current_applicants', [
            'all' => $applicantes->loadOnlyPending()
        ]);
    }
    
    public function accept($id)
    {
        $ad = new Applications();;
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /applicantes?error=notfound');
            exit;
        }

        if ($ad->Accept()) {
            header('Location: /applicantes?success=accepted');
            exit;
        }

        header('Location: /applicantes?error=fail');
        exit;
    }
    public function refuse($id)
    {
        $ad = new Applications();
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /applicantes?error=notfound');
            exit;
        }

        if ($ad->Refuse()) {
            header('Location: /applicantes?success=refused');
            exit;
        }

        header('Location: /applicantes?error=fail');
        exit;
    }

}