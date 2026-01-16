<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;

class Home extends BaseController
{
    public function index()
    {
        if (auth()->loggedIn()) {
            return redirect()->to('/dashboard');
        }
        return redirect()->to('/login');
    }

    public function dashboard()
    {
        $mahasiswaModel = new MahasiswaModel();
        $data = [
            'title' => 'Dashboard',
            'total_mahasiswa' => $mahasiswaModel->countAll(),
            'recent_mahasiswa' => $mahasiswaModel->orderBy('created_at', 'DESC')->limit(5)->findAll()
        ];
        
        return $this->render('dashboard', $data);
    }
}
