<?php

if (!function_exists('set_success_message')) {
    /**
     * Set success flash message
     */
    function set_success_message($message)
    {
        session()->setFlashdata('success', $message);
    }
}

if (!function_exists('set_error_message')) {
    /**
     * Set error flash message
     */
    function set_error_message($message, $details = null)
    {
        if ($details) {
            $message .= '<br><br><small class="text-muted">Detail: ' . $details . '</small>';
        }
        session()->setFlashdata('error', $message);
    }
}

if (!function_exists('set_warning_message')) {
    /**
     * Set warning flash message
     */
    function set_warning_message($message)
    {
        session()->setFlashdata('warning', $message);
    }
}

if (!function_exists('set_info_message')) {
    /**
     * Set info flash message
     */
    function set_info_message($message)
    {
        session()->setFlashdata('info', $message);
    }
}

if (!function_exists('set_permission_denied_message')) {
    /**
     * Set permission denied flash message
     */
    function set_permission_denied_message($message)
    {
        session()->setFlashdata('permission_denied', $message);
    }
}

if (!function_exists('set_db_error_message')) {
    /**
     * Set database error flash message
     */
    function set_db_error_message($message)
    {
        session()->setFlashdata('db_error', $message);
    }
}

if (!function_exists('set_validation_errors')) {
    /**
     * Set validation errors from validator
     */
    function set_validation_errors($validator)
    {
        $errors = $validator->getErrors();
        if (!empty($errors)) {
            session()->setFlashdata('errors', $errors);
        }
    }
}

if (!function_exists('handle_model_errors')) {
    /**
     * Handle model errors and set appropriate flash message
     */
    function handle_model_errors($model, $action = 'operasi')
    {
        $errors = $model->errors();
        if (!empty($errors)) {
            $errorMessage = "Gagal melakukan {$action}:<br><ul>";
            foreach ($errors as $error) {
                $errorMessage .= '<li>' . $error . '</li>';
            }
            $errorMessage .= '</ul>';
            set_error_message($errorMessage);
        } else {
            set_error_message("Gagal melakukan {$action}. Silakan coba lagi.");
        }
    }
}

if (!function_exists('handle_database_exception')) {
    /**
     * Handle database exceptions and set appropriate flash message
     */
    function handle_database_exception($exception, $action = 'operasi')
    {
        log_message('error', "Database error during {$action}: " . $exception->getMessage());
        
        $message = $exception->getMessage();
        
        // Handle common database errors
        if (strpos($message, 'foreign key constraint') !== false) {
            set_error_message("Data tidak dapat dihapus karena masih memiliki relasi dengan data lain");
        } elseif (strpos($message, 'Duplicate entry') !== false) {
            set_error_message("Data yang sama sudah ada di sistem");
        } elseif (strpos($message, 'Data too long') !== false) {
            set_error_message("Data yang dimasukkan terlalu panjang");
        } elseif (strpos($message, 'Connection refused') !== false) {
            set_error_message("Tidak dapat terhubung ke database. Silakan coba lagi.");
        } else {
            set_db_error_message("Terjadi kesalahan database: " . $message);
        }
    }
}

if (!function_exists('redirect_with_success')) {
    /**
     * Redirect with success message
     */
    function redirect_with_success($url, $message)
    {
        set_success_message($message);
        return redirect()->to($url);
    }
}

if (!function_exists('redirect_with_error')) {
    /**
     * Redirect with error message
     */
    function redirect_with_error($url, $message, $withInput = false)
    {
        set_error_message($message);
        $redirect = redirect()->to($url);
        return $withInput ? $redirect->withInput() : $redirect;
    }
}

if (!function_exists('back_with_error')) {
    /**
     * Redirect back with error message and input
     */
    function back_with_error($message)
    {
        set_error_message($message);
        return redirect()->back()->withInput();
    }
}

if (!function_exists('back_with_validation_errors')) {
    /**
     * Redirect back with validation errors and input
     */
    function back_with_validation_errors($validator)
    {
        set_validation_errors($validator);
        return redirect()->back()->withInput();
    }
}