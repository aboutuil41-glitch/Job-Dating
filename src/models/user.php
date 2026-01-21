<?php
namespace App\models;

use PDO;

require __DIR__ . '/../../vendor/autoload.php';

use App\core\BaseModel;

class user extends BaseModel
{
protected $name;
protected $email;
protected $password;
protected $role;
protected $created_at;
protected $updated_at;


public function findByEmail(string $email): ?array
{
    $stmt = $this->db->prepare(
        "SELECT * FROM {$this->getTable()} WHERE email = :email"
    );
    $stmt -> execute(['email' => $email]);
    $user = $stmt ->fetch(PDO::FETCH_ASSOC);
    return $user ? $user : null;
}
public function verifyPassword(string $plainPassword, string $hashedPassword): bool
{
    return password_verify($plainPassword, $hashedPassword);
}
 public function setName($u) { $this->name = $u; }
    public function getUsername() { return $this->name; }

    public function setEmail($e) { $this->email = $e; }
    public function getEmail() { return $this->email; }

     public function setPassword($p) { $this->password = password_hash($p, PASSWORD_BCRYPT); }
    public function getPassword() { return $this->password; }

    public function setRole($r) { $this->role =$r; }
    public function getRole() { return $this->role; }
    public function createUser($data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->getTable()} (name, email, password, role, created_at, updated_at) 
            VALUES (:name, :email, :password, :role, NOW(), NOW())"
        );
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => $data['role'] ?? 'student'
        ]);
    }

    public function setDate($date) { $this->created_at = $date; }
    public function getDate() { return $this->created_at; }

    public function setuDate($udate) { $this->updated_at = $udate; }
    public function getuDate() { return $this->updated_at; }

protected function getTable(): string
{
    return 'users';
}
protected function getColumns(): array
{
    return ['name', 'email', 'password', 'role', 'created_at', 'updated_at'];
}
protected function fill(){
}

public function loadStudents(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->getTable()} WHERE role = 'student'"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function StudentsCount(): int
{
    $stmt = $this->db->prepare(
        "SELECT COUNT(*) as total FROM {$this->getTable()} WHERE role = 'student'"
    );
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return (int)($result['total'] ?? 0);
}

}
?>