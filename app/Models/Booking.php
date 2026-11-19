<?php
namespace App\Models;

/**
 * Modelul Booking - Moștenește BaseModel
 */
class Booking extends BaseModel
{
    protected int $userId;
    protected int $classId;
    protected string $bookingDate;
    protected string $status = 'confirmed';

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->userId,
            'class_id'     => $this->classId,
            'booking_date' => $this->bookingDate,
            'status'       => $this->status
        ];
    }
}
