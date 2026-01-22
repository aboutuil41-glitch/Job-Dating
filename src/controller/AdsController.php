<?php
namespace App\controller;

use PDO;
use App\core\BaseController;
use App\Models\Announcements;
use App\models\companies;


class AdsController extends BaseController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO("sqlite::memory:");
    }

    public function loadAll()
    {
        $adsModel = new Announcements();
        $ads = $adsModel->RenderAds();
        
        // Decode skills JSON for each ad
        foreach ($ads as &$ad) {
            if (isset($ad['skills'])) {
                $ad['skills_array'] = json_decode($ad['skills'], true) ?? [];
            }
        }

        echo $this->renderTwigBack('current_ads', [
            'all' => $ads
        ]);
    }

    public function showCreateForm()
    {
        $companyModel = new companies();
        return $this->renderTwigBack('create_ad_form', [
            'companies' => $companyModel->loadAll()
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Ads/New');
            exit;
        }

        $ad = new Announcements();
        $ad->setTitle($_POST['title'] ?? '');
        $ad->setCompanyId((int)($_POST['company_id'] ?? 0));
        $ad->setContractType($_POST['contract_type'] ?? '');
        $ad->setDescription($_POST['description'] ?? '');
        $ad->setLocation($_POST['location'] ?? '');
        
        // Handle JSON skills - store as-is (already JSON from frontend)
        $skills = $_POST['skills'] ?? '[]';
        // Validate it's valid JSON
        $decodedSkills = json_decode($skills, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $skills = '[]'; // Fallback to empty array if invalid JSON
        }
        $ad->setSkills($skills);
        
        $ad->setDeleted(0); 
        $ad->setCreatedAt(date('Y-m-d H:i:s'));

        if ($ad->create()) {
            header('Location: /Ads');
            exit;
        }

        header('Location: /Ads/New?error=1');
        exit;
    }

    public function showEditForm($id)
    {
        $adsModel = new Announcements();
        $adData = $adsModel->findById($id);
        
        // Decode skills JSON for display
        if ($adData && isset($adData['skills'])) {
            $adData['skills_array'] = json_decode($adData['skills'], true) ?? [];
        }
        
        return $this->renderTwigBack('edit_ad', [
            'ad' => $adData
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /Ads/Edit/$id");
            exit;
        }

        $ad = new Announcements();
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /Ads');
            exit;
        }

        $ad->setTitle($_POST['title'] ?? '');
        $ad->setCompanyId((int)($_POST['company_id'] ?? 0));
        $ad->setContractType($_POST['contract_type'] ?? '');
        $ad->setDescription($_POST['description'] ?? '');
        $ad->setLocation($_POST['location'] ?? '');
        
        // Handle JSON skills
        $skills = $_POST['skills'] ?? '[]';
        $decodedSkills = json_decode($skills, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $skills = '[]';
        }
        $ad->setSkills($skills);
        
        $ad->setUpdatedAt(date('Y-m-d H:i:s'));

        if ($ad->update()) {
            header('Location: /Ads');
            exit;
        }

        header("Location: /Ads/Edit/$id?error=1");
        exit;
    }

    public function delete($id)
    {
        $ad = new Announcements();
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /Ads/Archive?error=notfound');
            exit;
        }

        if ($ad->delete()) {
            header('Location: /Ads/Archive?success=deleted');
            exit;
        }

        header('Location: /Ads/Archive?error=fail');
        exit;
    }
    
    public function softDelete($id)
    {
        $ad = new Announcements();
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /Ads?error=notfound');
            exit;
        }

        if ($ad->softDelete()) {
            header('Location: /Ads?success=archived');
            exit;
        }

        header('Location: /Ads?error=fail');
        exit;
    }

    public function showArchived()
    {
        $adsModel = new Announcements();
        $ads = $adsModel->RenderArchivedAds();
        
        // Decode skills JSON for each archived ad
        foreach ($ads as &$ad) {
            if (isset($ad['skills'])) {
                $ad['skills_array'] = json_decode($ad['skills'], true) ?? [];
            }
        }

        echo $this->renderTwigBack('archived_ads', [
            'all' => $ads
        ]);
    }

    public function restore($id)
    {
        $ad = new Announcements();
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /Ads/Archive?error=notfound');
            exit;
        }

        if ($ad->restore()) {
            header('Location: /Ads/Archive?success=restored');
            exit;
        }

        header('Location: /Ads/Archive?error=fail');
        exit;
    }
    
    public function showrecent(){
        $ad = new Announcements();
        $Recent = $ad->RenderRecentAds();

        echo "<pre>";
        print_r($Recent);
        echo "</pre>";
    }
}