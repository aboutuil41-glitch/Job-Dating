<?php

namespace App\models;

use App\Core\BaseModel;
use PDO;

class announcements extends BaseModel
{

    protected $title;
    protected $company_id;
    protected $contract_type;
    protected $description;
    protected $location;
    protected $skills;
    protected $deleted;
    protected $created_at;
    protected $updated_at;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setCompanyId(int $companyId): void
    {
        $this->company_id = $companyId;
    }

    public function getCompanyId(): ?int
    {
        return $this->company_id;
    }

    public function setContractType(string $type): void
    {
        $this->contract_type = $type;
    }

    public function getContractType(): ?string
    {
        return $this->contract_type;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setSkills(string $skills): void
    {
        $this->skills = $skills;
    }

    public function getSkills(): ?string
    {
        return $this->skills;
    }

    public function setDeleted($deleted): void
{
    $this->deleted = (int)$deleted;
}


    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setCreatedAt(string $date): void
    {
        $this->created_at = $date;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setUpdatedAt(string $date): void
    {
        $this->updated_at = $date;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    protected function getTable(): string
    {
        return 'announcements';
    }

    protected function getColumns(): array
    {
        return [
            'title',
            'company_id',
            'contract_type',
            'description',
            'location',
            'skills',
            'deleted',
            'created_at',
            'updated_at'
        ];
    }

    protected function fill(): void {}

    public function AdCount(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM {$this->getTable()} WHERE deleted = 0"
        );
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

        public function RenderAds(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->getTable()} WHERE deleted = 0"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletedAdCount(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM {$this->getTable()} WHERE deleted = 1"
        );
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    public function RenderArchivedAds(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->getTable()} WHERE deleted = 1"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function softDelete(): bool
{
    if (!$this->id) return false;

    $sql = "UPDATE {$this->getTable()} SET deleted = 1 WHERE id = :id";
    $stmt = $this->db->prepare($sql);

    return $stmt->execute(['id' => $this->id]);
}

public function restore(): bool
{
    if (!$this->id) return false;

    $sql = "UPDATE {$this->getTable()} SET deleted = 0 WHERE id = :id";
    $stmt = $this->db->prepare($sql);

    return $stmt->execute(['id' => $this->id]);
}

}