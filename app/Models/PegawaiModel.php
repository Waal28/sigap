<?php

namespace App\Models;

use App\Entities\Pegawai;
use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table            = 'pegawais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Pegawai::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'nip',
        'nama',
        'jabatan',
        'masa_kerja',
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
        'user_id'    => 'required|is_natural_no_zero',
        'nip'        => 'required|max_length[50]',
        'nama'       => 'required|max_length[150]',
        'jabatan'    => 'required|max_length[100]',
        'masa_kerja' => 'required|max_length[50]',
    ];

    public function findByUserId(int $userId): ?Pegawai
    {
        /** @var Pegawai|null $pegawai */
        $pegawai = $this->where('user_id', $userId)->first();

        return $pegawai instanceof Pegawai ? $pegawai : null;
    }

    public function findByNip(string $nip): ?Pegawai
    {
        /** @var Pegawai|null $pegawai */
        $pegawai = $this->where('nip', $nip)->first();

        return $pegawai instanceof Pegawai ? $pegawai : null;
    }

    /**
     * Cari pegawai berdasarkan kata kunci dan/atau jabatan
     *
     * @return array<int, Pegawai>
     */
    public function search(?string $keyword = null, ?string $jabatan = null): array
    {
        $builder = $this;

        if ($keyword !== null && trim($keyword) !== '') {
            $keyword = trim($keyword);
            $builder = $builder->groupStart()
                ->like('nama', $keyword, 'both', null, true)
                ->orLike('nip', $keyword, 'both', null, true)
                ->orLike('jabatan', $keyword, 'both', null, true)
                ->orLike('masa_kerja', $keyword, 'both', null, true)
                ->groupEnd();
        }

        if ($jabatan !== null && trim($jabatan) !== '') {
            $builder = $builder->where('jabatan', trim($jabatan));
        }

        /** @var array<int, Pegawai> $results */
        $results = $builder->orderBy('id', 'ASC')->findAll();

        return $results;
    }

    /**
     * Dapatkan daftar jabatan unik untuk filter
     *
     * @return array<int, string>
     */
    public function getDistinctJabatan(): array
    {
        /** @var array<int, array<string, mixed>> $rows */
        $rows = $this->builder()
            ->select('jabatan')
            ->distinct()
            ->where('jabatan IS NOT NULL', null, false)
            ->where('jabatan !=', '')
            ->orderBy('jabatan', 'ASC')
            ->get()
            ->getResultArray();

        $list = [];
        foreach ($rows as $row) {
            $jb = (string) ($row['jabatan'] ?? '');
            if ($jb !== '') {
                $list[] = $jb;
            }
        }

        return $list;
    }
}
