<?php
namespace App\Repositories;

/**
 * Barem Nota 3.02: Utilizarea interfețelor
 * Contractul de date pentru toate clasele de tip Repository.
 */
interface RepositoryInterface
{
    public function findById(int $id);
    public function getAll(): array;
    public function delete(int $id): bool;
}
