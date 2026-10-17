<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

/**
 * @property int|null $id
 * @property int $user_id
 * @property string $nip
 * @property string $nama
 * @property string $jabatan
 * @property string $masa_kerja
 * @property Time|string|null $created_at
 * @property Time|string|null $updated_at
 */
class Pegawai extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'id'      => 'integer',
        'user_id' => 'integer',
    ];
}
