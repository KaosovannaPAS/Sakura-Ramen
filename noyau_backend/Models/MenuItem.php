<?php

namespace App\Models;

use App\Core\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    public function getByCategory($categoryId)
    {
        if (!$this->db) return [];
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE category_id = ? AND is_available = 1 ORDER BY display_order ASC");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }
}
