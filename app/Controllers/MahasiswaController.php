<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use CodeIgniter\HTTP\ResponseInterface;

class MahasiswaController extends BaseController
{
    protected $mahasiswaModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        helper('form');
    }

    public function index()
    {
        $data['mahasiswa'] = $this->mahasiswaModel->findAll();
        return view('mahasiswa/index', $data);
    }

    public function create()
    {
        return view('mahasiswa/create');
    }

    public function store()
    {
        // Manual validation for create
        $currentYear = date('Y');
        $rules = [
            'nim' => 'required|min_length[3]|max_length[20]|is_unique[mahasiswa.nim]',
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[mahasiswa.email]',
            'jurusan' => 'required|in_list[Teknik Informatika,Sistem Informasi,Teknik Komputer,Teknik Elektro,Teknik Industri]',
            'angkatan' => "required|integer|greater_than[2014]|less_than_equal_to[{$currentYear}]"
        ];

        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }

        $data = [
            'nim' => $this->request->getPost('nim'),
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'jurusan' => $this->request->getPost('jurusan'),
            'angkatan' => $this->request->getPost('angkatan'),
        ];

        try {
            if ($this->mahasiswaModel->skipValidation(true)->save($data)) {
                return redirect_with_success('/mahasiswa', 'Data mahasiswa berhasil ditambahkan');
            } else {
                set_error_message('Gagal menambahkan data mahasiswa. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'penambahan data mahasiswa');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data['mahasiswa'] = $this->mahasiswaModel->find($id);
        
        if (!$data['mahasiswa']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('mahasiswa/edit', $data);
    }

    public function update($id)
    {
        $mahasiswa = $this->mahasiswaModel->find($id);
        
        if (!$mahasiswa) {
            set_error_message('Data mahasiswa tidak ditemukan');
            return redirect()->to('/mahasiswa');
        }

        // Manual validation with proper ID exclusion
        $currentYear = date('Y');
        $rules = [
            'nim' => 'required|min_length[3]|max_length[20]|is_unique[mahasiswa.nim,id,' . $id . ']',
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[mahasiswa.email,id,' . $id . ']',
            'jurusan' => 'required|in_list[Teknik Informatika,Sistem Informasi,Teknik Komputer,Teknik Elektro,Teknik Industri]',
            'angkatan' => "required|integer|greater_than[2014]|less_than_equal_to[{$currentYear}]"
        ];

        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }

        $data = [
            'nim' => $this->request->getPost('nim'),
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'jurusan' => $this->request->getPost('jurusan'),
            'angkatan' => $this->request->getPost('angkatan'),
        ];

        try {
            if ($this->mahasiswaModel->skipValidation(true)->update($id, $data)) {
                return redirect_with_success('/mahasiswa', 'Data mahasiswa berhasil diperbarui');
            } else {
                set_error_message('Gagal memperbarui data mahasiswa. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'pembaruan data mahasiswa');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        $mahasiswa = $this->mahasiswaModel->find($id);
        
        if (!$mahasiswa) {
            set_error_message('Data mahasiswa tidak ditemukan');
            return redirect()->to('/mahasiswa');
        }

        try {
            if ($this->mahasiswaModel->delete($id)) {
                return redirect_with_success('/mahasiswa', 'Data mahasiswa berhasil dihapus');
            } else {
                handle_model_errors($this->mahasiswaModel, 'penghapusan data mahasiswa');
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'penghapusan data mahasiswa');
        }

        return redirect()->to('/mahasiswa');
    }
}
