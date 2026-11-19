<?php
namespace App\Repositories;

use App\Models\User;

/**
 * Repository pentru utilizatori (Anti-SQL Injection via Prepared Statements).
 */
class UserRepository extends BaseRepository
{
    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? (new User($row))->setId((int)$row['id']) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(:email) LIMIT 1");
        $stmt->execute(['email' => trim($email)]);
        $row = $stmt->fetch();
        return $row ? (new User($row))->setId((int)$row['id']) : null;
    }

    public function getAll(): array
    {
        $sql = "SELECT u.*, r.name AS role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (role_id, name, email, password_hash, phone, avatar)
            VALUES (:r, :n, :e, :p, :ph, :a)
        ");

        $stmt->execute([
            'r'  => $data['role_id'] ?? 2,
            'n'  => $data['name'],
            'e'  => strtolower(trim($data['email'])),
            'p'  => $data['password_hash'],
            'ph' => $data['phone'] ?? null,
            'a'  => $data['avatar'] ?? 'default-avatar.png'
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
