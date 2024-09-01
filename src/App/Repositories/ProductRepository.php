<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class ProductRepository
{
    private $db;

    /**
     * @param $db
     */
    public function __construct($db = null)
    {
        $this->db = $db ?? new Database();
    }

    public function getAll():array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query('Select * from products');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}