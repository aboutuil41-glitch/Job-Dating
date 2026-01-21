<?php
namespace App\controller;

use App\core\BaseController;
use App\models\companies;

class CompanyController extends BaseController
{
    public function loadAll()
    {
        $companyModel = new companies();

        echo $this->renderTwigBack('current_companies', [
            'all' => $companyModel->loadAll()
        ]);
    }

    public function showCreateForm()
    {
        return $this->renderTwigBack('create_company_form', []);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Job-Dating/public/AddCompany/new');
            exit;
        }

        $company = new companies();
        $company->setName($_POST['name'] ?? '');
        $company->setEmail($_POST['email'] ?? '');
        $company->setSector($_POST['sector'] ?? '');
        $company->setLocation($_POST['location'] ?? '');
        $company->setPhone($_POST['phone'] ?? '');
        $company->setCreatedAt(date('Y-m-d H:i:s'));

        if ($company->create()) {
            header('Location: /Job-Dating/public/CompanyIndex');
            exit;
        }

        header('Location: /Job-Dating/public/AddCompany/new?error=1');
        exit;
    }

    public function showEditForm($id)
    {
        $companyModel = new companies();
        return $this->renderTwigBack('edit_company', [
            'company' => $companyModel->findById($id)
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /Job-Dating/public/Company/Edit/$id");
            exit;
        }

        $company = new companies();
        $company = $company->loadById($id);

        if (!$company) {
            header('Location: /Job-Dating/public/CompanyIndex');
            exit;
        }

        $company->setName($_POST['name'] ?? '');
        $company->setEmail($_POST['email'] ?? '');
        $company->setSector($_POST['sector'] ?? '');
        $company->setLocation($_POST['location'] ?? '');
        $company->setPhone($_POST['phone'] ?? '');
        $company->setUpdatedAt(date('Y-m-d H:i:s'));

        if ($company->update()) {
            header('Location: /Job-Dating/public/CompanyIndex');
            exit;
        }

        header("Location: /Job-Dating/public/Company/Edit/$id?error=1");
        exit;
    }

    public function delete($id)
    {
        $company = new companies();
        $company = $company->loadById($id);

        if (!$company) {
            header('Location: /Job-Dating/public/CompanyIndex?error=notfound');
            exit;
        }

        if ($company->delete()) {
            header('Location: /Job-Dating/public/CompanyIndex?success=deleted');
            exit;
        }

        header('Location: /Job-Dating/public/CompanyIndex?error=fail');
        exit;
    }
}