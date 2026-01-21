<?php
namespace App\Controller;

use App\Core\BaseController;
use App\Core\Auth;
use App\Core\Database;

class CompanyController extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function loadAll()
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("SELECT * FROM companies ORDER BY name");
        $stmt->execute();
        $companies = $stmt->fetchAll();
        
        return $this->renderTwig('backoffice/companies/index.twig', [
            'companies' => $companies
        ]);
    }

    public function showCreateForm()
    {
        Auth::requireAuth();
        return $this->renderTwig('backoffice/companies/create.twig');
    }

    public function store()
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("INSERT INTO companies (name, sector, location, email, phone, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $_POST['name'],
            $_POST['sector'],
            $_POST['location'],
            $_POST['email'],
            $_POST['phone']
        ]);
        
        header('Location: /CompanyIndex');
        exit;
    }

    public function showEditForm($id)
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("SELECT * FROM companies WHERE id = ?");
        $stmt->execute([$id]);
        $company = $stmt->fetch();
        
        return $this->renderTwig('backoffice/companies/edit.twig', [
            'company' => $company
        ]);
    }

    public function update($id)
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("UPDATE companies SET name = ?, sector = ?, location = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([
            $_POST['name'],
            $_POST['sector'],
            $_POST['location'],
            $_POST['email'],
            $_POST['phone'],
            $id
        ]);
        
        header('Location: /CompanyIndex');
        exit;
    }

    public function delete($id)
    {
        Auth::requireAuth();
        
        $stmt = $this->db->prepare("DELETE FROM companies WHERE id = ?");
        $stmt->execute([$id]);
        
        header('Location: /CompanyIndex');
        exit;
    }
}