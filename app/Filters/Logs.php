<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Logs implements FilterInterface
{
    protected $db; 

    public function before(RequestInterface $request, $arguments = null){}
   
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Skip logging CLI & debugging tools
        if (is_cli() || ENVIRONMENT === 'testing') {
            return;
        }
        
        $param = [
            'route' => empty($request->getUri()->getSegments()) ? 'login' : implode('/', $request->getUri()->getSegments()), 
            'method' => $request->getMethod(), 
            'is_ajax' => $request->isAjax(),
            'create_date' => date('Y-m-d H:i:s'), 
            'id_user' => session()->get('user_id') ?? '9999', 
            'active' => '1'
        ];

        // POST
        if($request->getPost()){
            $param['data'][] =  $request->getPost();
        }

        // GET
        if(count($request->getGet())>0){
            $param['data'][] =  $request->getGet();
        }
        
        // AJAX
        if($request->getVar()){
            $param['data'][] = $request->getVar();
        }
        
        $path =  WRITEPATH .'logs/activity/'; //exit(dd($path));
        if (!file_exists($path)) {
            mkdir($path, 755, true);
        }
        if (!is_dir($path) || !is_writable($path)) {
            throw new Exception('Could not save. Directory is not writable.');
        }

        $log = $path.date('Y-m-d').'.log'; //exit(dd($log));
        file_put_contents($log, serialize($param)."\n", FILE_APPEND | LOCK_EX);

        // Pastikan File Ada!
        if (file_exists($log) && session()->get('user_id')) {

            try {

                if (service('request')->getUserAgent()->isBrowser()) {
                    // Browser
                    $user_agent = service('request')->getUserAgent()->getBrowser() . ' ' . service('request')->getUserAgent()->getVersion();
                } elseif (service('request')->getUserAgent()->isRobot()) {
                    // Robot
                    $user_agent = service('request')->getUserAgent()->getRobot();
                } elseif (service('request')->getUserAgent()->isMobile()) {
                    // Mobile
                    $user_agent = service('request')->getUserAgent()->getMobile();
                } else {
                    // Not listed
                    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
                }
    
                $this->db = db_connect();
                $newParam = [
                    'route' => $param['route'],
                    'method' => $param['method'],
                    'is_ajax' => $param['is_ajax'] ? '1' : '0', // Convert boolean to character
                    'create_date' => $param['create_date'],
                    'id_user' => $param['id_user'],
                    'active' => $param['active'],
                    'data' => (!empty($param['data'])) ? json_encode($param['data']) : 'modul',
                    'browser' => $user_agent,
                    'platform' => service('request')->getUserAgent()->getPlatform()
                ];
                $this->db->table('user_apps_activity')->insert($newParam);

            } catch (\Throwable $e) {
                // Log the error for debugging
                log_message('error', 'Failed to insert user activity log: ' . $e->getMessage());
                log_message('error', 'Data attempted: ' . json_encode($newParam ?? []));
            }
            
        }
        
    }  
   
}