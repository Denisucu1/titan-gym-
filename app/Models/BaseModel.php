<?php
namespace App\Models;

/**
 * Barem Nota 3.01 & 4.01: Clase și Moștenire
 * Clasă abstractă de bază pentru toate entitățile din aplicație.
 */
abstract class BaseModel
{
    protected ?int $id = null;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $val) {
            $setter = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($this, $setter)) {
                $this->$setter($val);
            } elseif (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
        return $this;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }

    abstract public function toArray(): array;
}
