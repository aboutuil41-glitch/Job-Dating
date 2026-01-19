<?php

namespace App\Core;

class Validator
{
    private $errors = [];
    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function required($field)
    {
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field][] = "The {$field} field is required.";
        }
        return $this;
    }

    public function email($field)
    {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "The {$field} must be a valid email.";
        }
        return $this;
    }

    public function min($field, $length)
    {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field][] = "The {$field} must be at least {$length} characters.";
        }
        return $this;
    }

    public function max($field, $length)
    {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field][] = "The {$field} must not exceed {$length} characters.";
        }
        return $this;
    }

    public function unique($field, $table, $column)
    {
        if (isset($this->data[$field])) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = :value");
            $stmt->execute(['value' => $this->data[$field]]);
            
            if ($stmt->fetchColumn() > 0) {
                $this->errors[$field][] = "The {$field} is already taken.";
            }
        }
        return $this;
    }

    public function fails()
    {
        return !empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    public static function make($data)
    {
        return new self($data);
    }
}