<?php
namespace App\Controller;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\Database;

class AdsController extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function loadAll()
    {
        Auth::requireAuth();
        
        $sql = "SELECT a.*, c.name as company_name FROM announcements a 
                JOIN companies c ON a.company_id = c.id 
                WHERE a.deleted = 0 ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $ads = $stmt->fetchAll();
        
        return $this->renderTwig('backoffice/ads/index.twig', [
            'ads' => $ads
        ]);
    }

    public function showCreateForm()
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("SELECT id, name FROM companies ORDER BY name");
        $stmt->execute();
        $companies = $stmt->fetchAll();
        
        return $this->renderTwig('backoffice/ads/create.twig', [
            'companies' => $companies
        ]);
    }

    public function store()
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("INSERT INTO announcements (title, company_id, contract_type, description, location, skills, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $_POST['title'],
            $_POST['company_id'],
            $_POST['contract_type'],
            $_POST['description'],
            $_POST['location'],
            $_POST['skills']
        ]);
        
        header('Location: /Ads');
        exit;
    }

    public function showEditForm($id)
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("SELECT * FROM announcements WHERE id = ?");
        $stmt->execute([$id]);
        $ad = $stmt->fetch();
        
        $stmt = $this->db->prepare("SELECT id, name FROM companies ORDER BY name");
        $stmt->execute();
        $companies = $stmt->fetchAll();
        
        return $this->renderTwig('backoffice/ads/edit.twig', [
            'ad' => $ad,
            'companies' => $companies
        ]);
    }

    public function update($id)
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("UPDATE announcements SET title = ?, company_id = ?, contract_type = ?, description = ?, location = ?, skills = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([
            $_POST['title'],
            $_POST['company_id'],
            $_POST['contract_type'],
            $_POST['description'],
            $_POST['location'],
            $_POST['skills'],
            $id
        ]);
        
        header('Location: /Ads');
        exit;
    }

    public function softDelete($id)
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("UPDATE announcements SET deleted = 1 WHERE id = ?");
        $stmt->execute([$id]);
        
        header('Location: /Ads');
        exit;
    }

    public function showArchived()
    {
        Auth::requireAuth();
        
        $sql = "SELECT a.*, c.name as company_name FROM announcements a 
                JOIN companies c ON a.company_id = c.id 
                WHERE a.deleted = 1 ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $ads = $stmt->fetchAll();
        
        return $this->renderTwig('backoffice/ads/archive.twig', [
            'ads' => $ads
        ]);
    }

    public function restore($id)
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("UPDATE announcements SET deleted = 0 WHERE id = ?");
        $stmt->execute([$id]);
        
        header('Location: /Ads/Archive');
        exit;
    }

    public function delete($id)
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->execute([$id]);
        
        header('Location: /Ads/Archive');
        exit;
    }
}