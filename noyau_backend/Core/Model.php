<?php

namespace App\Core;

use PDO;

abstract class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        try {
            $this->db = Database::getMySQL();
        } catch (\PDOException $e) {
            $this->db = null;
        }
    }

    public function all()
    {
        if (!$this->db) return [];
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        if (!$this->db) return null;
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        if (!$this->db) return 0;
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})");
        $stmt->execute(array_values($data));
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        if (!$this->db) return false;
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "{$key} = ?, ";
        }
        $fields = rtrim($fields, ', ');

        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$fields} WHERE id = ?");
        $values = array_values($data);
        $values[] = $id;

        return $stmt->execute($values);
    }

    public function delete($id)
    {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
