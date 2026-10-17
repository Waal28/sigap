<?php

namespace App\Models;

use App\Entities\User;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = User::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nip',
        'password',
        'role',
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
        'nip'      => 'required|max_length[50]|is_unique[users.nip,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[ADMIN,PEGAWAI,PEJABAT_PENILAI]',
    ];

    /**
     * Cari user berdasarkan NIP
     */
    public function findByNip(string $nip): ?User
    {
        return $this->where('nip', $nip)->first();
    }
}
