<?php
namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * Barem Nota 2.03, 3.02 & 4.01: Repository Pattern, Interfețe și Moștenire
 */
abstract class BaseRepository implements RepositoryInterface
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}
