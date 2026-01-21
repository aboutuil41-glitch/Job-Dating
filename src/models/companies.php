<?php
namespace App\models;

use PDO;

require __DIR__ . '/../../vendor/autoload.php';

use App\core\BaseModel;

class companies extends BaseModel
{
    protected $name;
    protected $sector;
    protected $location;
    protected $email;
    protected $phone;
    protected $created_at;
    protected $updated_at;

    public function setName($n) { $this->name = $n; }
    public function getName() { return $this->name; }

    public function setEmail($e) { $this->email = $e; }
    public function getEmail() { return $this->email; }

    public function setSector($s) { $this->sector = $s; }
    public function getSector() { return $this->sector; }

    public function setLocation($l) { $this->location = $l; }
    public function getLocation() { return $this->location; }

    public function setPhone($p) { $this->phone = $p; }
    public function getPhone() { return $this->phone; }

    public function setCreatedAt($date) { $this->created_at = $date; }
    public function getCreatedAt() { return $this->created_at; }

    public function setUpdatedAt($udate) { $this->updated_at = $udate; }
    public function getUpdatedAt() { return $this->updated_at; }

    protected function getTable(): string
    {
        return 'companies';
    }

    protected function getColumns(): array
    {
        return ['name', 'sector', 'location', 'email', 'phone', 'created_at', 'updated_at'];
    }

    protected function fill() {}

    public function CompaniesCount(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM {$this->getTable()}"
        );
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    public function RenderCompanies(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->getTable()}"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>