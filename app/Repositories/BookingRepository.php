<?php
namespace App\Repositories;

use App\Models\Booking;

/**
 * Repository pentru rezervări și plăți Stripe.
 */
class BookingRepository extends BaseRepository
{
    public function findById(int $id): ?Booking
    {
        $stmt = $this->db->prepare("SELECT * FROM bookings WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? (new Booking($row))->setId((int)$row['id']) : null;
    }

    public function getAll(): array
    {
        return $this->getAllBookings();
    }

    public function getAllBookings(): array
    {
        $sql = "
            SELECT b.*, u.name AS user_name, u.email AS user_email, c.title AS class_title, c.schedule_day
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            JOIN classes c ON b.class_id = c.id
            ORDER BY b.booking_date DESC, b.id DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getUserBookings(int $userId): array
    {
        $sql = "
            SELECT b.*, c.title AS class_title, c.schedule_day, c.price, c.duration_minutes
            FROM bookings b
            JOIN classes c ON b.class_id = c.id
            WHERE b.user_id = :u
            ORDER BY b.booking_date DESC, b.id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['u' => $userId]);
        return $stmt->fetchAll();
    }

    public function isAlreadyBooked(int $userId, int $classId, string $date): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM bookings
            WHERE user_id = :u AND class_id = :c AND booking_date = :d AND status = 'confirmed'
        ");
        $stmt->execute(['u' => $userId, 'c' => $classId, 'd' => $date]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function createBooking(int $userId, int $classId, string $date): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO bookings (user_id, class_id, booking_date, status)
            VALUES (:u, :c, :d, 'confirmed')
        ");
        $stmt->execute(['u' => $userId, 'c' => $classId, 'd' => $date]);
        return (int)$this->db->lastInsertId();
    }

    public function cancelBooking(int $bookingId, int $userId, bool $isAdmin = false): bool
    {
        if ($isAdmin) {
            $stmt = $this->db->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = :id");
            return $stmt->execute(['id' => $bookingId]);
        }
        $stmt = $this->db->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = :id AND user_id = :u");
        return $stmt->execute(['id' => $bookingId, 'u' => $userId]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM bookings WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getMemberships(): array
    {
        return $this->db->query("SELECT * FROM memberships ORDER BY price ASC")->fetchAll();
    }

    public function getMembershipById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM memberships WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function recordPayment(int $userId, string $stripeId, float $amount, string $itemType, int $itemId): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO payments (user_id, stripe_payment_id, amount, currency, status, item_type, item_id)
            VALUES (:u, :s, :a, 'ron', 'succeeded', :t, :i)
        ");
        $stmt->execute([
            'u' => $userId,
            's' => $stripeId,
            'a' => $amount,
            't' => $itemType,
            'i' => $itemId
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function activateSubscription(int $userId, int $membershipId, int $durationDays, int $paymentId): int
    {
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime("+{$durationDays} days"));

        $stmt = $this->db->prepare("
            INSERT INTO subscriptions (user_id, membership_id, payment_id, start_date, end_date, status)
            VALUES (:u, :m, :p, :s, :e, 'active')
        ");
        $stmt->execute([
            'u' => $userId,
            'm' => $membershipId,
            'p' => $paymentId,
            's' => $start,
            'e' => $end
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getAllPayments(): array
    {
        $sql = "
            SELECT p.*, u.name AS user_name, u.email AS user_email
            FROM payments p
            JOIN users u ON p.user_id = u.id
            ORDER BY p.id DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }
}
