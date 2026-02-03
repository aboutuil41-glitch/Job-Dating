<?php
namespace App\controller;

use PDO;
use App\core\BaseController;
use App\Models\Announcements;


class HomeController extends BaseController
{
public function home(){
    $admodel = new announcements();
    $this->renderTwigFront('home',[
    'ads' => $admodel->RenderRecentAds()
    ]);
}

}