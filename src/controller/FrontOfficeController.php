<?php
namespace App\Controller;

use App\Core\BaseController;
use App\Core\Auth;
use App\Models\Announcements;
use App\models\companies;

class FrontOfficeController extends BaseController
{
    public function home()
    {
        Auth::requireAuth();
        $adsModel = new Announcements();
        $companiesModel = new Companies();
        $this -> renderTwigFront('home',
        [
            'user' => Auth::user(),
            'jobs' => $adsModel -> RenderAds(),
            'companies' => $companiesModel -> loadAll()
        ]);
    }
    
    public function jobs()
    {
        Auth::requireAuth();
        
        $adsModel = new Announcements();
        
        return $this->renderTwig('jobs', [
            'user' => Auth::user(),
            'jobs' => $adsModel->RenderAds()
        ]);
    }
    public function searchJobs()
    {
        Auth::requireAuth();
        $adsModel = new Announcements();
        $query = $_GET['q'] ?? '';
        $company = $_GET['company'] ?? '';
        $contracts =  $_GET['contract'] ?? [];
        $sql = "SELECT a.*, c.name as company_name FROM announcements a 
            JOIN companies c ON a.company_id = c.id 
            WHERE a.deleted = 0";
        $params = [];
        if (!empty($query)) {
            $sql .= " AND (a.title LIKE :query OR a.description LIKE :query)";
            $params['query'] = '%' . $query . '%';
        }

        if (!empty($company)) {
            $sql .= " AND a.company_id = :company";
            $params['company'] = $company;
        }

        $sql .= " ORDER BY a.created_at DESC";

        $stmt = $adsModel->db->prepare($sql);
        $stmt->execute($params);
        $jobs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($jobs);
        exit;
    }
    public function jobDetails($id)
    {
        Auth::requireAuth();
        
        $adsModel = new Announcements();
        
        return $this->renderTwig('job_details', [
            'user' => Auth::user(),
            'job' => $adsModel->findById($id)
        ]);
    }
}