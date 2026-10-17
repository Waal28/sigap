<?php

namespace App\Models;

use App\Entities\LampiranPengajuan;
use CodeIgniter\Model;

class LampiranPengajuanModel extends Model
{
    protected $table            = 'lampiran_pengajuans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = LampiranPengajuan::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pengajuan_id',
        'nama_dokumen',
        'file_path',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'pengajuan_id' => 'required|is_natural_no_zero',
        'nama_dokumen' => 'required|max_length[255]',
        'file_path'    => 'required|max_length[255]',
    ];

    /**
     * @return array<int, LampiranPengajuan>
     */
    public function getByPengajuanId(int $pengajuanId): array
    {
        return $this->where('pengajuan_id', $pengajuanId)->findAll();
    }
}
