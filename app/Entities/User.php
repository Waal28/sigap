<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

/**
 * @property int|null $id
 * @property string $nip
 * @property string $password
 * @property string $role
 * @property Time|string|null $created_at
 * @property Time|string|null $updated_at
 */
class User extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Otomatis melakukan hash password jika diset
     */
    public function setPassword(string $pass): self
    {
        $this->attributes['password'] = password_hash($pass, PASSWORD_BCRYPT);

        return $this;
    }
}
