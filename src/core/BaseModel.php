<?php

namespace App\core;

use PDO;

use App\core\Database;

abstract class BaseModel
{
    protected $db;
    protected int $id;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
public function getId(): ?int {
    return $this->id;
}
public function setId(int $id): void {
    $this->id = $id;
}

    public function loadAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->getTable()}"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(): bool
    {
        $columns = $this->getColumns();
        $placeholders = array_map(fn($c) => ":$c", $columns);

        $sql = "INSERT INTO {$this->getTable()} (" . implode(',', $columns) . ")
                VALUES (" . implode(',', $placeholders) . ")";

        $stmt = $this->db->prepare($sql);

        $data = [];
        foreach ($columns as $col) {
            $data[$col] = $this->$col ?? null;
        }

        if ($stmt->execute($data)) {
            $this->id = (int)$this->db->lastInsertId();
            return true;
        }
        return false;
    }

    public function update(): bool
    {
        if (!$this->id) return false;

        $columns = $this->getColumns();
        $set = implode(', ', array_map(fn($c) => "$c = :$c", $columns));

        $stmt = $this->db->prepare(
            "UPDATE {$this->getTable()} SET $set WHERE id = :id"
        );

        $data = [];
        foreach ($columns as $col) {
            $data[$col] = $this->$col ?? null;
        }
        $data['id'] = $this->id;

        return $stmt->execute($data);
    }



    public function updateOnly(array $fields): bool
{
    if (!$this->id || empty($fields)) return false;

    $set = implode(', ', array_map(fn($c) => "$c = :$c", $fields));

    $sql = "UPDATE {$this->getTable()} SET $set WHERE id = :id";
    $stmt = $this->db->prepare($sql);

    $data = ['id' => $this->id];

    foreach ($fields as $field) {
        $data[$field] = $this->$field ?? null;
    }

    return $stmt->execute($data);
}

public function loadById(int $id): ?static
{
    $sql = "SELECT * FROM {$this->getTable()} WHERE id = :id LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$data) return null;

    foreach ($data as $key => $value) {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }
    }

    $this->id = $id;
    return $this;
}



    public function delete(): bool
    {
        if (!$this->id) return false;

        $stmt = $this->db->prepare(
            "DELETE FROM {$this->getTable()} WHERE id = :id"
        );
        return $stmt->execute(['id' => $this->id]);
    }
    

        public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->getTable()} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    abstract protected function getTable(): string;
    abstract protected function getColumns(): array;
}
