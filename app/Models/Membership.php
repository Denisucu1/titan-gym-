<?php
namespace App\Models;

class Membership extends BaseModel
{
    protected string $name = '';
    protected float $price = 0.0;
    protected int $durationDays = 30;
    protected string $description = '';
    protected string $badge = 'Standard';

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'price'         => $this->price,
            'duration_days' => $this->durationDays,
            'description'   => $this->description,
            'badge'         => $this->badge
        ];
    }
}
