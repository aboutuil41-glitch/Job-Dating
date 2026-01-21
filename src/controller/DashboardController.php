<?php

namespace App\controller;

use App\core\BaseController;
use App\models\user;
use App\models\companies;
use App\models\announcements;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel = new user();
        $companyModel = new companies();
        $adsModel = new Announcements();

        echo $this->renderTwigBack('dashboard', [
            'studentsCount' => $userModel->StudentsCount(),
            'companiesCount' => $companyModel->CompaniesCount(),
            'adsCount' => $adsModel->AdCount(),
            'archivedAdsCount' => $adsModel->deletedAdCount()
        ]);
    }
}