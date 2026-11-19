<?php
namespace App\Repositories;

use App\Models\GymClass;

/**
 * Repository pentru gestiunea claselor de antrenament.
 * Folosește exclusiv Prepared Statements pentru a elimina riscul de SQL Injection (Nota 9.03).
 */
class ClassRepository extends BaseRepository
{
    public function findById(int $id): ?GymClass
    {
        $sql = "
            SELECT c.*, cat.name AS category_name, t.name AS trainer_name,
                   (SELECT COUNT(*) FROM bookings b WHERE b.class_id = c.id AND b.status = 'confirmed') AS booked_count
            FROM classes c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN trainers t ON c.trainer_id = t.id
            WHERE c.id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $class = new GymClass($row);
        $class->setCategoryName($row['category_name'] ?? null);
        $class->setTrainerName($row['trainer_name'] ?? null);
        $class->setBookedCount((int)($row['booked_count'] ?? 0));
        return $class;
    }

    public function getAll(): array
    {
        return $this->getAllWithDetails();
    }

    public function getAllWithDetails(?int $categoryId = null): array
    {
        $sql = "
            SELECT c.*, cat.name AS category_name, t.name AS trainer_name,
                   (SELECT COUNT(*) FROM bookings b WHERE b.class_id = c.id AND b.status = 'confirmed') AS booked_count
            FROM classes c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN trainers t ON c.trainer_id = t.id
        ";

        $params = [];
        if ($categoryId) {
            $sql .= " WHERE c.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $sql .= " ORDER BY c.id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $classes = [];
        foreach ($rows as $row) {
            $c = new GymClass($row);
            $c->setCategoryName($row['category_name'] ?? null);
            $c->setTrainerName($row['trainer_name'] ?? null);
            $c->setBookedCount((int)($row['booked_count'] ?? 0));
            $classes[] = $c;
        }

        return $classes;
    }

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO classes (category_id, trainer_id, title, description, schedule_day, duration_minutes, capacity, price, image_url)
            VALUES (:cat, :tr, :title, :desc, :day, :dur, :cap, :pr, :img)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'cat'   => $data['category_id'],
            'tr'    => $data['trainer_id'],
            'title' => $data['title'],
            'desc'  => $data['description'],
            'day'   => $data['schedule_day'],
            'dur'   => $data['duration_minutes'] ?? 60,
            'cap'   => $data['capacity'] ?? 20,
            'pr'    => $data['price'] ?? 0.0,
            'img'   => $data['image_url'] ?? 'default-class.jpg'
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE classes
            SET category_id = :cat,
                trainer_id = :tr,
                title = :title,
                description = :desc,
                schedule_day = :day,
                duration_minutes = :dur,
                capacity = :cap,
                price = :pr,
                image_url = :img
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'    => $id,
            'cat'   => $data['category_id'],
            'tr'    => $data['trainer_id'],
            'title' => $data['title'],
            'desc'  => $data['description'],
            'day'   => $data['schedule_day'],
            'dur'   => $data['duration_minutes'],
            'cap'   => $data['capacity'],
            'pr'    => $data['price'],
            'img'   => $data['image_url']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM classes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getCategories(): array
    {
        return $this->db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
    }

    public function getTrainers(): array
    {
        return $this->db->query("SELECT * FROM trainers ORDER BY name ASC")->fetchAll();
    }
}
