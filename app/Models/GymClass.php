<?php
namespace App\Models;

/**
 * Modelul GymClass - Moștenește BaseModel
 */
class GymClass extends BaseModel
{
    protected int $categoryId = 1;
    protected int $trainerId = 1;
    protected string $title = '';
    protected string $description = '';
    protected string $scheduleDay = '';
    protected int $durationMinutes = 60;
    protected int $capacity = 20;
    protected float $price = 0.00;
    protected ?string $imageUrl = 'default-class.jpg';

    protected ?string $categoryName = null;
    protected ?string $trainerName = null;
    protected int $bookedCount = 0;

    public function getCategoryId(): int { return $this->categoryId; }
    public function setCategoryId(int $id): self { $this->categoryId = $id; return $this; }

    public function getTrainerId(): int { return $this->trainerId; }
    public function setTrainerId(int $id): self { $this->trainerId = $id; return $this; }

    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $desc): self { $this->description = $desc; return $this; }

    public function getScheduleDay(): string { return $this->scheduleDay; }
    public function setScheduleDay(string $day): self { $this->scheduleDay = $day; return $this; }

    public function getDurationMinutes(): int { return $this->durationMinutes; }
    public function setDurationMinutes(int $m): self { $this->durationMinutes = $m; return $this; }

    public function getCapacity(): int { return $this->capacity; }
    public function setCapacity(int $c): self { $this->capacity = $c; return $this; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $p): self { $this->price = $p; return $this; }

    public function getImageUrl(): ?string { return $this->imageUrl; }
    public function setImageUrl(?string $url): self { $this->imageUrl = $url; return $this; }

    public function getCategoryName(): ?string { return $this->categoryName; }
    public function setCategoryName(?string $n): self { $this->categoryName = $n; return $this; }

    public function getTrainerName(): ?string { return $this->trainerName; }
    public function setTrainerName(?string $n): self { $this->trainerName = $n; return $this; }

    public function getBookedCount(): int { return $this->bookedCount; }
    public function setBookedCount(int $b): self { $this->bookedCount = $b; return $this; }

    public function getAvailableSpots(): int
    {
        return max(0, $this->capacity - $this->bookedCount);
    }

    public function isFull(): bool
    {
        return $this->getAvailableSpots() <= 0;
    }

    public function toArray(): array
    {
        return [
            'id'               => $this->id,
            'category_id'      => $this->categoryId,
            'trainer_id'       => $this->trainerId,
            'title'            => $this->title,
            'description'      => $this->description,
            'schedule_day'     => $this->scheduleDay,
            'duration_minutes' => $this->durationMinutes,
            'capacity'         => $this->capacity,
            'price'            => $this->price,
            'image_url'        => $this->imageUrl,
            'category_name'    => $this->categoryName,
            'trainer_name'     => $this->trainerName,
            'available_spots'  => $this->getAvailableSpots()
        ];
    }
}
