<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

/**
 * @property int|null $id
 * @property int $pengajuan_id
 * @property string $nama_dokumen
 * @property string $file_path
 * @property Time|string|null $created_at
 * @property Time|string|null $updated_at
 */
class LampiranPengajuan extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'id'           => 'integer',
        'pengajuan_id' => 'integer',
    ];
}
