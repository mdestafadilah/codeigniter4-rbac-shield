<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nim' => '123456789',
                'nama' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@student.ac.id',
                'jurusan' => 'Teknik Informatika',
                'angkatan' => 2021,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nim' => '987654321',
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.ac.id',
                'jurusan' => 'Sistem Informasi',
                'angkatan' => 2022,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nim' => '456789123',
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@student.ac.id',
                'jurusan' => 'Teknik Komputer',
                'angkatan' => 2020,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('mahasiswa')->insertBatch($data);
    }
}
