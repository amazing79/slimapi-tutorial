<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class ProductRepository
{
    private $db;

    /**
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAll():array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query('Select * from products');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}