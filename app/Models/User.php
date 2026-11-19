<?php
namespace App\Models;

/**
 * Modelul User - Moștenește BaseModel (Barem Nota 4.01)
 */
class User extends BaseModel
{
    protected int $roleId = 2; // 1 = Admin, 2 = Client
    protected string $name = '';
    protected string $email = '';
    protected string $passwordHash = '';
    protected ?string $phone = null;
    protected ?string $avatar = 'default-avatar.png';
    protected ?string $createdAt = null;

    public function getRoleId(): int { return $this->roleId; }
    public function setRoleId(int $roleId): self { $this->roleId = $roleId; return $this; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = strtolower(trim($email)); return $this; }

    public function getPasswordHash(): string { return $this->passwordHash; }
    public function setPasswordHash(string $hash): self { $this->passwordHash = $hash; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): self { $this->phone = $phone; return $this; }

    public function getAvatar(): ?string { return $this->avatar; }
    public function setAvatar(?string $avatar): self { $this->avatar = $avatar; return $this; }

    public function isAdmin(): bool
    {
        return $this->roleId === 1;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->id,
            'role_id'  => $this->roleId,
            'name'     => $this->name,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'avatar'   => $this->avatar,
            'is_admin' => $this->isAdmin()
        ];
    }
}
