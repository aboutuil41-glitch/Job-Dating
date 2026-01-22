<?php
namespace App\Controller;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Announcements;
use App\models\companies;
use App\models\Applications;
use PDO;

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

        return $this->renderTwigFront('jobs', [
            'user' => Auth::user(),
            'jobs' => $adsModel->RenderAds()
        ]);
    }

    public function searchJobs()
    {
        Auth::requireAuth();
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

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($jobs);
        exit;
    }

    public function jobDetails($id)
    {
        Auth::requireAuth();

        $applicationsModel = new Applications();
        $user = Auth::user();

        // Get job with company details
        $sql = "SELECT a.*, c.name as company_name, c.sector as company_sector, 
                   c.location as company_location, c.email as company_email, c.phone as company_phone
            FROM announcements a 
            JOIN companies c ON a.company_id = c.id 
            WHERE a.id = :id AND a.deleted = 0";

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$job) {
            header('Location: /Job-Dating/public/jobs');
            exit;
        }

        // Check if user already applied
        $hasApplied = $applicationsModel->hasUserApplied($user['id'], $id);

        return $this->renderTwigFront('job_details', [
            'user' => $user,
            'job' => $job,
            'hasApplied' => $hasApplied
        ]);
    }

    public function applyToJob($id)
    {
        Auth::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /Job-Dating/public/job/$id");
            exit;
        }

        $user = Auth::user();
        $applicationsModel = new Applications();

        // Check if already applied
        if ($applicationsModel->hasUserApplied($user['id'], $id)) {
            header("Location: /Job-Dating/public/job/$id?error=already_applied");
            exit;
        }

        // Create application
        $applicationsModel->setUserId($user['id']);
        $applicationsModel->setAnnouncementId($id);
        $applicationsModel->setFullName($_POST['full_name'] ?? '');
        $applicationsModel->setEmail($_POST['email'] ?? '');
        $applicationsModel->setCoverLetter($_POST['cover_letter'] ?? null);
        $applicationsModel->setStatus('pending');
        $applicationsModel->setCreatedAt(date('Y-m-d H:i:s'));

        if ($applicationsModel->create()) {
            header("Location: /Job-Dating/public/job/$id?success=applied");
        } else {
            header("Location: /Job-Dating/public/job/$id?error=failed");
        }
        exit;
    }
}