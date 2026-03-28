<?php

namespace App\Models;

use App\Core\Model;

class MenuCategory extends Model
{
    protected $table = 'menu_categories';

    public function getAllActive()
    {
        if (!$this->db) return [];
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY display_order ASC");
        return $stmt->fetchAll();
    }
}
