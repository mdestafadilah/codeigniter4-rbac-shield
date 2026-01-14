<?php

if (!function_exists('get_update_validation_rules')) {
    /**
     * Get validation rules for update operations
     * Automatically replaces {id} placeholder with actual ID
     */
    function get_update_validation_rules($model, $id)
    {
        $rules = $model->getValidationRules();
        
        // Replace {id} placeholder with actual ID for all rules
        foreach ($rules as $field => $rule) {
            if (strpos($rule, '{id}') !== false) {
                $rules[$field] = str_replace('{id}', $id, $rule);
            }
        }
        
        return $rules;
    }
}

if (!function_exists('get_unique_validation_rule')) {
    /**
     * Generate is_unique validation rule with proper ID exclusion
     */
    function get_unique_validation_rule($table, $field, $id = null)
    {
        if ($id !== null) {
            return "is_unique[{$table}.{$field},id,{$id}]";
        }
        return "is_unique[{$table}.{$field}]";
    }
}

if (!function_exists('build_validation_rules')) {
    /**
     * Build validation rules dynamically for create/update operations
     */
    function build_validation_rules($baseRules, $isUpdate = false, $id = null)
    {
        if (!$isUpdate) {
            return $baseRules;
        }
        
        $rules = $baseRules;
        
        // Update is_unique rules to exclude current record ID
        foreach ($rules as $field => $rule) {
            if (strpos($rule, 'is_unique[') !== false && $id !== null) {
                // Extract table.field from is_unique[table.field]
                preg_match('/is_unique\[([^\]]+)\]/', $rule, $matches);
                if (isset($matches[1])) {
                    $tableField = $matches[1];
                    // Replace the is_unique rule with ID exclusion
                    $rules[$field] = str_replace(
                        "is_unique[{$tableField}]",
                        "is_unique[{$tableField},id,{$id}]",
                        $rule
                    );
                }
            }
        }
        
        return $rules;
    }
}

if (!function_exists('validate_model_data')) {
    /**
     * Validate data using model rules with proper ID handling
     */
    function validate_model_data($controller, $model, $data, $id = null)
    {
        $isUpdate = ($id !== null);
        $rules = $model->getValidationRules();
        
        // Build proper validation rules
        $validationRules = build_validation_rules($rules, $isUpdate, $id);
        
        return $controller->validate($validationRules);
    }
}

if (!function_exists('get_user_validation_rules')) {
    /**
     * Get user validation rules for create/update
     */
    function get_user_validation_rules($id = null, $requirePassword = false)
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'role_id' => 'required|integer',
            'is_active' => 'in_list[false,true]'
        ];
        
        // Add unique validation
        $rules['username'] .= '|' . get_unique_validation_rule('users', 'username', $id);
        $rules['email'] .= '|' . get_unique_validation_rule('users', 'email', $id);
        
        
        // Add password validation if required
        if ($requirePassword) {
            $rules['password'] = 'required|min_length[6]';
            $rules['confirm_password'] = 'required|matches[password]';
        } elseif ($id !== null) {
            // For updates, password is optional
            $rules['password'] = 'permit_empty|min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }
        
        return $rules;
    }
}

if (!function_exists('get_mahasiswa_validation_rules')) {
    /**
     * Get mahasiswa validation rules for create/update
     */
    function get_mahasiswa_validation_rules($id = null)
    {
        $currentYear = date('Y');
        
        $rules = [
            'nim' => 'required|min_length[3]|max_length[20]',
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'jurusan' => 'required|in_list[Teknik Informatika,Sistem Informasi,Teknik Komputer,Teknik Elektro,Teknik Industri]',
            'angkatan' => "required|integer|greater_than[2014]|less_than_equal_to[{$currentYear}]"
        ];
        
        // Add unique validation
        $rules['nim'] .= '|' . get_unique_validation_rule('mahasiswa', 'nim', $id);
        $rules['email'] .= '|' . get_unique_validation_rule('mahasiswa', 'email', $id);
        
        return $rules;
    }
}

if (!function_exists('get_role_validation_rules')) {
    /**
     * Get role validation rules for create/update
     */
    function get_role_validation_rules($id = null)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]',
            'display_name' => 'required|min_length[3]|max_length[100]',
            'description' => 'max_length[255]',
            'is_active' => 'in_list[false,true]'
        ];
        
        // Add unique validation
        $rules['name'] .= '|' . get_unique_validation_rule('roles', 'name', $id);
        
        return $rules;
    }
}

if (!function_exists('get_permission_validation_rules')) {
    /**
     * Get permission validation rules for create/update
     */
    function get_permission_validation_rules($id = null)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]',
            'display_name' => 'required|min_length[3]|max_length[100]',
            'module' => 'required|max_length[50]',
            'description' => 'max_length[255]'
        ];
        
        // Add unique validation
        $rules['name'] .= '|' . get_unique_validation_rule('permissions', 'name', $id);
        
        return $rules;
    }
}

if (!function_exists('validate_with_custom_rules')) {
    /**
     * Validate request data with custom rules
     */
    function validate_with_custom_rules($controller, $rules)
    {
        if (!$controller->validate($rules)) {
            return back_with_validation_errors($controller->validator);
        }
        return true;
    }
}

// ========================================
// Validation Error Messages (Bahasa Indonesia)
// ========================================

if (!function_exists('validation_rules_id')) {
    /**
     * Generate validation rules dengan custom error messages bahasa Indonesia
     * 
     * @param string $field Nama field
     * @param string $rules Validation rules (e.g., 'required|min_length[3]')
     * @param array $customMessages Custom messages untuk override (optional)
     * @return array ['rules' => string, 'errors' => array]
     * 
     * @example
     * validation_rules_id('nama', 'required|min_length[3]')
     * // Returns: ['rules' => 'required|min_length[3]', 'errors' => ['required' => 'Nama harus diisi', ...]]
     */
    function validation_rules_id(string $field, string $rules, array $customMessages = []): array
    {
        $ruleArray = explode('|', $rules);
        $errors = [];
        $label = get_field_label_id($field);
        
        foreach ($ruleArray as $rule) {
            // Parse rule dan parameter
            $ruleName = $rule;
            $param = '';
            
            if (strpos($rule, '[') !== false) {
                preg_match('/([a-z_]+)\[(.+)\]/', $rule, $matches);
                if (!empty($matches)) {
                    $ruleName = $matches[1];
                    $param = $matches[2];
                }
            }
            
            // Gunakan custom message jika ada, jika tidak gunakan default
            if (isset($customMessages[$ruleName])) {
                $errors[$ruleName] = $customMessages[$ruleName];
            } else {
                $errors[$ruleName] = get_error_message_id($ruleName, $label, $param);
            }
        }
        
        return [
            'rules' => $rules,
            'errors' => $errors
        ];
    }
}

if (!function_exists('get_error_message_id')) {
    /**
     * Mendapatkan pesan error validasi dalam bahasa Indonesia
     * 
     * @param string $rule Nama rule
     * @param string $label Label field (sudah dalam bahasa Indonesia)
     * @param string $param Parameter rule
     * @return string
     */
    function get_error_message_id(string $rule, string $label, string $param = ''): string
    {
        $messages = [
            // Basic
            'required' => '{label} harus diisi',
            'permit_empty' => '{label} boleh kosong',
            
            // Length
            'min_length' => '{label} minimal {param} karakter',
            'max_length' => '{label} maksimal {param} karakter',
            'exact_length' => '{label} harus tepat {param} karakter',
            
            // Numeric
            'integer' => '{label} harus berupa bilangan bulat',
            'numeric' => '{label} harus berupa angka',
            'decimal' => '{label} harus berupa angka desimal',
            'greater_than' => '{label} harus lebih besar dari {param}',
            'greater_than_equal_to' => '{label} harus lebih besar atau sama dengan {param}',
            'less_than' => '{label} harus kurang dari {param}',
            'less_than_equal_to' => '{label} harus kurang dari atau sama dengan {param}',
            'is_natural' => '{label} hanya boleh berisi angka',
            'is_natural_no_zero' => '{label} harus lebih besar dari nol',
            
            // List
            'in_list' => '{label} tidak valid',
            'not_in_list' => '{label} tidak boleh salah satu dari: {param}',
            
            // Uniqueness
            'is_unique' => '{label} sudah terdaftar',
            'is_not_unique' => '{label} harus sudah ada dalam database',
            
            // Email & URL
            'valid_email' => 'Format email tidak valid',
            'valid_emails' => 'Semua email harus valid',
            'valid_url' => 'Format URL tidak valid',
            'valid_url_strict' => 'Format URL tidak valid',
            
            // Date
            'valid_date' => 'Format tanggal tidak valid',
            
            // File Upload
            'uploaded' => '{label} harus di-upload',
            'max_size' => 'Ukuran {label} maksimal {param}KB',
            'is_image' => 'File harus berupa gambar',
            'mime_in' => 'Tipe file tidak valid',
            'ext_in' => 'Ekstensi file tidak valid',
            'max_dims' => 'Dimensi gambar terlalu besar',
            'min_dims' => 'Dimensi gambar terlalu kecil',
            
            // String
            'alpha' => '{label} hanya boleh berisi huruf',
            'alpha_dash' => '{label} hanya boleh berisi huruf, angka, underscore, dan dash',
            'alpha_numeric' => '{label} hanya boleh berisi huruf dan angka',
            'alpha_numeric_space' => '{label} hanya boleh berisi huruf, angka, dan spasi',
            'alpha_space' => '{label} hanya boleh berisi huruf dan spasi',
            
            // Other
            'matches' => '{label} tidak cocok',
            'differs' => '{label} harus berbeda dari {param}',
            'equals' => '{label} harus sama dengan: {param}',
            'regex_match' => 'Format {label} tidak valid',
        ];
        
        $message = $messages[$rule] ?? '{label} tidak valid';
        
        // Replace placeholders
        $message = str_replace('{label}', $label, $message);
        $message = str_replace('{param}', $param, $message);
        
        return $message;
    }
}

if (!function_exists('get_field_label_id')) {
    /**
     * Mapping field name ke label bahasa Indonesia
     * 
     * @param string $field
     * @return string
     */
    function get_field_label_id(string $field): string
    {
        $labels = [
            // Common
            'nama' => 'Nama',
            'nama_lengkap' => 'Nama lengkap',
            'email' => 'Email',
            'password' => 'Password',
            'username' => 'Username',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
            'deskripsi' => 'Deskripsi',
            'alamat' => 'Alamat',
            'telepon' => 'Telepon',
            'no_hp' => 'Nomor HP',
            'nomor_hp' => 'Nomor HP',
            
            // Jamaah
            'jenis_kelamin' => 'Jenis kelamin',
            'tempat_lahir' => 'Tempat lahir',
            'tanggal_lahir' => 'Tanggal lahir',
            'no_ktp' => 'Nomor KTP',
            'no_passport' => 'Nomor passport',
            'kategori_program' => 'Kategori program',
            'status_jamaah' => 'Status jamaah',
            'golongan_darah' => 'Golongan darah',
            'status_pernikahan' => 'Status pernikahan',
            'pekerjaan' => 'Pekerjaan',
            'kontak_darurat_nama' => 'Nama kontak darurat',
            'kontak_darurat_hp' => 'No HP kontak darurat',
            'hubungan_kontak_darurat' => 'Hubungan kontak darurat',
            
            // Location
            'negara' => 'Nama negara',
            'negara_id' => 'Negara',
            'kota' => 'Nama kota',
            'kota_id' => 'Kota',
            'provinsi' => 'Provinsi',
            
            // Hotel & Tourism
            'nama_hotel' => 'Nama hotel',
            'rating' => 'Rating',
            'link_maps' => 'Link maps',
            'nama_tour' => 'Nama tour',
            'kategori_id' => 'Kategori',
            'nama_kategori' => 'Nama kategori',
            'jenis_kategori' => 'Jenis kategori',
            
            // Airport & Airlines
            'nama_bandara' => 'Nama bandara',
            'kode_iata' => 'Kode IATA',
            'nama_maskapai' => 'Nama maskapai',
            'is_transit' => 'Jenis penerbangan',
            'durasi_transit' => 'Durasi transit',
            
            // Other
            'judul' => 'Judul',
            'url' => 'URL',
            'caption' => 'Caption',
            'gambar' => 'Gambar',
        ];
        
        return $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }
}
