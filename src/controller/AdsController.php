<?php
namespace App\controller;

use PDO;
use App\core\BaseController;
use App\Models\Announcements;

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

        echo $this->renderTwigBack('current_ads', [
            'all' => $adsModel->RenderAds()
        ]);
    }

    public function showCreateForm()
    {
        return $this->renderTwigBack('create_ad_form', []);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Job-Dating/public/Ads/New');
            exit;
        }

        $ad = new Announcements();
        $ad->setTitle($_POST['title'] ?? '');
        $ad->setCompanyId((int)($_POST['company_id'] ?? 0));
        $ad->setContractType($_POST['contract_type'] ?? '');
        $ad->setDescription($_POST['description'] ?? '');
        $ad->setLocation($_POST['location'] ?? '');
        $ad->setSkills($_POST['skills'] ?? '');
        $ad->setDeleted(0); 
        $ad->setCreatedAt(date('Y-m-d H:i:s'));

        if ($ad->create()) {
            header('Location: /Job-Dating/public/Ads');
            exit;
        }

        header('Location: /Job-Dating/public/Ads/New?error=1');
        exit;
    }

    public function showEditForm($id)
    {
        $adsModel = new Announcements();
        return $this->renderTwigBack('edit_ad', [
            'ad' => $adsModel->findById($id)
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /Job-Dating/public/Ads/Edit/$id");
            exit;
        }

        $ad = new Announcements();
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /Job-Dating/public/Ads');
            exit;
        }

        $ad->setTitle($_POST['title'] ?? '');
        $ad->setCompanyId((int)($_POST['company_id'] ?? 0));
        $ad->setContractType($_POST['contract_type'] ?? '');
        $ad->setDescription($_POST['description'] ?? '');
        $ad->setLocation($_POST['location'] ?? '');
        $ad->setSkills($_POST['skills'] ?? '');
        $ad->setUpdatedAt(date('Y-m-d H:i:s'));

        if ($ad->update()) {
            header('Location: /Job-Dating/public/Ads');
            exit;
        }

        header("Location: /Job-Dating/public/Ads/Edit/$id?error=1");
        exit;
    }

    public function delete($id)
    {
        $ad = new Announcements();
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /Job-Dating/public/Ads/Archive?error=notfound');
            exit;
        }

        if ($ad->delete()) {
            header('Location: /Job-Dating/public/Ads/Archive?success=deleted');
            exit;
        }

        header('Location: /Job-Dating/public/Ads/Archive?error=fail');
        exit;
    }
        public function softDelete($id)
    {
        $ad = new Announcements();
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /Job-Dating/public/Ads?error=notfound');
            exit;
        }

        if ($ad->softDelete()) {
            header('Location: /Job-Dating/public/Ads?success=archived');
            exit;
        }

        header('Location: /Job-Dating/public/Ads?error=fail');
        exit;
    }

  public function showArchived()
    {
        $adsModel = new Announcements();

        echo $this->renderTwigBack('archived_ads', [
            'all' => $adsModel->RenderArchivedAds()
        ]);
    }


     public function restore($id)
    {
        $ad = new Announcements();
        $ad = $ad->loadById($id);

        if (!$ad) {
            header('Location: /Job-Dating/public/Ads/Archive?error=notfound');
            exit;
        }

        if ($ad->restore()) {
            header('Location: /Job-Dating/public/Ads/Archive?success=restored');
            exit;
        }

        header('Location: /Job-Dating/public/Ads/Archive?error=fail');
        exit;
    }
}

