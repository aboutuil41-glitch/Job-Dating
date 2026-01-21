<?php
namespace App\Controller;
use App\Core\BaseController;
use App\Core\Auth;
use App\Core\Database;
class JobController extends BaseController
{
    private $db;
    public function __construct()
    {
       $this -> db = Database::getInstance()->getConnection();
    }
    public function home()
    {
        Auth::requireAuth();
        $companies = $this -> getCompanies();
        $jobs = $this -> getJobs();
        return $this->renderTwig('frontoffice/home.twig',[
            'companies' => $companies,
            'jobs' => $jobs
        ]);
    }
    private function getCompanies()
    {
        $stmt = $this ->db->prepare("SELECT id , name FROM companies ORDER BY name");
        $stmt -> execute();
        return $stmt -> fetchAll();
    }
    private function getJobs()
    {
        $sql = $this ->db->prepare("SELECT a.id,a.title,a.description,a.location,a.contract_type,a.skills,a.created_at,c.name as company_name FROM announcements a JOIN companies c ON a.company_id = c.id WHERE a.deleted = 0 ORDER BY a.created_at DESC");
        $stmt = $this -> db->prepare($sql);
        $stmt -> execute();
        return $stmt -> fetchAll();
    }
    public function search()
    {
        $query = $_GET['q'] ?? '';
        $company = $_GET['company'] ?? '';
        $contracts = $_GET['contract'] ?? [];
    }
}