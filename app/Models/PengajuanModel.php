<?php

namespace App\Models;

use App\Entities\Pengajuan;
use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table            = 'pengajuans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Pengajuan::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pegawai_id',
        'jenis_kenaikan',
        'status',
        'catatan',
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
        'pegawai_id'     => 'required|is_natural_no_zero',
        'jenis_kenaikan' => 'required|in_list[Gaji,Pangkat]',
        'status'         => 'required|in_list[Menunggu,Disetujui,Ditolak]',
    ];

    /**
     * @return array<int, Pengajuan>
     */
    public function getByPegawaiId(int $pegawaiId): array
    {
        /** @var array<int, Pengajuan> $results */
        $results = $this->where('pegawai_id', $pegawaiId)->orderBy('created_at', 'DESC')->findAll();

        return $results;
    }

    /**
     * Ambil data pengajuan beserta relasi pegawai dan jumlah lampiran
     *
     * @param string|array<int, string>|null $status
     * @return array<int, array<string, mixed>>
     */
    public function getWithPegawai(?string $keyword = null, ?string $jenisKenaikan = null, string|array|null $status = null, ?int $pegawaiId = null): array
    {
        $builder = $this->builder()
            ->select('pengajuans.*, pegawais.nama, pegawais.nip, pegawais.jabatan, pegawais.masa_kerja, COUNT(lampiran_pengajuans.id) as total_lampiran')
            ->join('pegawais', 'pegawais.id = pengajuans.pegawai_id', 'left')
            ->join('lampiran_pengajuans', 'lampiran_pengajuans.pengajuan_id = pengajuans.id', 'left')
            ->groupBy('pengajuans.id, pegawais.id');

        if ($pegawaiId !== null && $pegawaiId > 0) {
            $builder->where('pengajuans.pegawai_id', $pegawaiId);
        }

        if ($jenisKenaikan !== null && trim($jenisKenaikan) !== '') {
            $builder->where('pengajuans.jenis_kenaikan', trim($jenisKenaikan));
        }

        if (is_array($status) && ! empty($status)) {
            $builder->whereIn('pengajuans.status', $status);
        } elseif (is_string($status) && trim($status) !== '') {
            $builder->where('pengajuans.status', trim($status));
        }

        if ($keyword !== null && trim($keyword) !== '') {
            $keyword = trim($keyword);
            $builder->groupStart()
                ->like('pegawais.nama', $keyword, 'both', null, true)
                ->orLike('pegawais.nip', $keyword, 'both', null, true)
                ->orLike('pegawais.jabatan', $keyword, 'both', null, true)
                ->orLike('pengajuans.jenis_kenaikan', $keyword, 'both', null, true)
                ->orLike('pengajuans.status', $keyword, 'both', null, true)
                ->groupEnd();
        }

        /** @var array<int, array<string, mixed>> $rows */
        $rows = $builder->orderBy('pengajuans.created_at', 'DESC')->get()->getResultArray();

        return $rows;
    }
}
