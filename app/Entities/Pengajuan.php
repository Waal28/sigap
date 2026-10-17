<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

/**
 * @property int|null $id
 * @property int $pegawai_id
 * @property string $jenis_kenaikan
 * @property string $status
 * @property string|null $catatan
 * @property Time|string|null $created_at
 * @property Time|string|null $updated_at
 */
class Pengajuan extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'id'         => 'integer',
        'pegawai_id' => 'integer',
    ];
}
