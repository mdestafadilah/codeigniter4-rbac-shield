<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nim', 'nama', 'email', 'jurusan', 'angkatan'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nim'      => 'required|max_length[20]|is_unique[mahasiswa.nim,id,{id}]',
        'nama'     => 'required|max_length[100]',
        'email'    => 'required|valid_email|is_unique[mahasiswa.email,id,{id}]',
        'jurusan'  => 'required|max_length[100]',
        'angkatan' => 'required|numeric'
    ];
    protected $validationMessages   = [
        'nim' => [
            'required' => 'NIM harus diisi',
            'max_length' => 'NIM tidak boleh lebih dari 20 karakter',
            'is_unique' => 'NIM sudah digunakan oleh mahasiswa lain'
        ],
        'nama' => [
            'required' => 'Nama harus diisi',
            'max_length' => 'Nama tidak boleh lebih dari 100 karakter'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Email harus berformat valid',
            'is_unique' => 'Email sudah digunakan oleh mahasiswa lain'
        ],
        'jurusan' => [
            'required' => 'Jurusan harus dipilih',
            'max_length' => 'Jurusan tidak boleh lebih dari 100 karakter'
        ],
        'angkatan' => [
            'required' => 'Angkatan harus diisi',
            'numeric' => 'Angkatan harus berupa angka'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
