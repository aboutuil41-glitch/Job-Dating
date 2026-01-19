<?php
namespace App\models;

use PDO;

require __DIR__ . '/../../vendor/autoload.php';

use App\core\BaseModel;

class user extends BaseModel
{
protected $username;
protected $email;
protected $password;
protected $bio;
protected $created_at;


 public function setUsername($u) { $this->username = $u; }
    public function getUsername() { return $this->username; }

    public function setEmail($e) { $this->email = $e; }
    public function getEmail() { return $this->email; }

     public function setPassword($p) { $this->password = password_hash($p, PASSWORD_BCRYPT); }
    public function getPassword() { return $this->password; }

    public function setBio($b) { $this->bio = $b; }
    public function getBio() { return $this->bio; }

    public function setDate($date) { $this->created_at = $date; }
    public function getDate() { return $this->created_at; }

protected function getTable(): string
{
    return 'users';
}
protected function getColumns(): array
{
    return ['username', 'email', 'password', 'bio', 'created_at'];
}
protected function fill(){


}
}






?>