<?php
namespace App\models;
use App\Core\BaseModel;
use PDO;
class Applications extends BaseModel
{
    protected $user_id;
    protected $announcement_id;
    protected $full_name;
    protected $email;
    protected $cover_letter;
    protected $resume_path;
    protected $status;
    protected $created_at;
    public function setUserId(int $userId): void { $this->user_id = $userId; }
    public function getUserId(): ?int { return $this->user_id; }

    public function setAnnouncementId(int $announcementId): void { $this->announcement_id = $announcementId; }
    public function getAnnouncementId(): ?int { return $this->announcement_id; }

    public function setFullName(string $fullName): void { $this->full_name = $fullName; }
    public function getFullName(): ?string { return $this->full_name; }

    public function setEmail(string $email): void { $this->email = $email; }
    public function getEmail(): ?string { return $this->email; }

    public function setCoverLetter(?string $coverLetter): void { $this->cover_letter = $coverLetter; }
    public function getCoverLetter(): ?string { return $this->cover_letter; }

    public function setResumePath(?string $resumePath): void { $this->resume_path = $resumePath; }
    public function getResumePath(): ?string { return $this->resume_path; }

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
            'full_name',
            'email',
            'cover_letter',
            'resume_path',
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