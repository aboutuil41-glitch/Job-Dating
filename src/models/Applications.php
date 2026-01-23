<?php
namespace App\models;
use App\Core\BaseModel;
use PDO;

class Applications extends BaseModel
{
    protected $user_id;
    protected $announcement_id;
    protected $first_name;
    protected $last_name;
    protected $email;
    protected $name;
    protected $specialization;
    protected $promotion;
    protected $motivational_message;
    protected $status;
    protected $created_at;

    public function setUserId(int $userId): void { $this->user_id = $userId; }
    public function getUserId(): ?int { return $this->user_id; }

    public function setAnnouncementId(int $announcementId): void { $this->announcement_id = $announcementId; }
    public function getAnnouncementId(): ?int { return $this->announcement_id; }

    public function setFirstName(string $firstName): void { $this->first_name = $firstName; }
    public function getFirstName(): ?string { return $this->first_name; }

    public function setLastName(string $lastName): void { $this->last_name = $lastName; }
    public function getLastName(): ?string { return $this->last_name; }

    public function setEmail(string $email): void { $this->email = $email; }
    public function getEmail(): ?string { return $this->email; }

    public function setName(string $name): void { $this->name = $name; }
    public function getName(): ?string { return $this->name; }

    public function setSpecialization(string $specialization): void { $this->specialization = $specialization; }
    public function getSpecialization(): ?string { return $this->specialization; }

    public function setPromotion(string $promotion): void { $this->promotion = $promotion; }
    public function getPromotion(): ?string { return $this->promotion; }

    public function setMotivationalMessage(?string $message): void { $this->motivational_message = $message; }
    public function getMotivationalMessage(): ?string { return $this->motivational_message; }

    public function setStatus(string $status): void { $this->status = $status; }
    public function getStatus(): ?string { return $this->status; }

    public function setCreatedAt(string $date): void { $this->created_at = $date; }
    public function getCreatedAt(): ?string { return $this->created_at; }

    protected function getTable(): string
    {
        return 'applications';
    }

    protected function getColumns(): array
    {
        return [
            'user_id',
            'announcement_id',
            'first_name',
            'last_name',
            'email',
            'name',
            'specialization',
            'promotion',
            'motivational_message',
            'status',
            'created_at'
        ];
    }

    protected function fill(): void {}

    public function hasUserApplied(int $userId, int $announcementId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count FROM {$this->getTable()} 
             WHERE user_id = :user_id AND announcement_id = :announcement_id"
        );
        $stmt->execute([
            'user_id' => $userId,
            'announcement_id' => $announcementId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0) > 0;
    }
}