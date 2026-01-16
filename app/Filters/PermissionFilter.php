<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class PermissionFilter implements FilterInterface
{
    /**
     * Check if user has required permission
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        // Get required permission from arguments
        $requiredPermission = $arguments[0] ?? null;
        
        if (!$requiredPermission) {
            return; // No permission required
        }

        $userId = auth()->id();
        $userModel = new UserModel(); //exit(dd($requiredPermission));
        
        // Check if user has the required permission
        if (!$userModel->hasPermission($userId, $requiredPermission)) {
            helper('message');
            // Store the attempted URL for potential redirect after login
            set_permission_denied_message('Anda tidak memiliki izin untuk mengakses resource ini.');
            
            // Return 403 Forbidden or redirect to access denied page
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON(['error' => 'Akses ditolak. Izin tidak mencukupi.']);
            }
            // Tidak Punya Akses
            return redirect()->to('/dashboard');
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
