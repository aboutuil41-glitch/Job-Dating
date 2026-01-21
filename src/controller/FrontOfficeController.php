<?php
namespace App\Controller;

use App\Core\BaseController;
use App\Core\Auth;
use App\Models\Announcements;

class FrontOfficeController extends BaseController
{
    public function home()
    {
        Auth::requireAuth();
        
        $adsModel = new Announcements();
        
        return $this->renderTwig('frontoffice/home', [
            'user' => Auth::user(),
            'jobs' => $adsModel->RenderAds()
        ]);
    }
    
    public function jobs()
    {
        Auth::requireAuth();
        
        $adsModel = new Announcements();
        
        return $this->renderTwig('frontoffice/jobs', [
            'user' => Auth::user(),
            'jobs' => $adsModel->RenderAds()
        ]);
    }
    
    public function jobDetails($id)
    {
        Auth::requireAuth();
        
        $adsModel = new Announcements();
        
        return $this->renderTwig('frontoffice/job_details', [
            'user' => Auth::user(),
            'job' => $adsModel->findById($id)
        ]);
    }
}