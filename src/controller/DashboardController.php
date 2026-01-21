<?php
namespace App\Controller;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\Database;

class DashboardController extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function index()
    {
        Auth::requireAuth();
        
        $user = Auth::user();
        
        if ($user['role'] === 'admin') {
            // Admin dashboard
            $stats = $this->getAdminStats();
            return $this->renderTwig('backoffice/dashboard.twig', [
                'user' => $user,
                'stats' => $stats
            ]);
        } else {
            // Student dashboard - redirect to home
            header('Location: /home');
            exit;
        }
    }

    private function getAdminStats()
    {
        $stats = [];
        
        // Count students
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = 'student'");
        $stmt->execute();
        $stats['students'] = $stmt->fetchColumn();
        
        // Count companies
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM companies");
        $stmt->execute();
        $stats['companies'] = $stmt->fetchColumn();
        
        // Count active ads
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM announcements WHERE deleted = 0");
        $stmt->execute();
        $stats['active_ads'] = $stmt->fetchColumn();
        
        return $stats;
    }
}