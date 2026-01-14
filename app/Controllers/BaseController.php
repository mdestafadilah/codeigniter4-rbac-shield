<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Config\Services;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['setting', 'rbac', 'message', 'validation', 'network', 'menu'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;
    
    /**
     * Store locale
     */
    protected $locale;

    /**
     * Store data
     */
    protected $data;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->data['scripts'] = []; // <- PENTING: selalu ada (default array)
        $this->data['styles'] = []; // <- PENTING: selalu ada (default array)

        // Shield: Make authenticated user available in all views
        $this->data['user'] = auth()->user();

        // Load dynamic menus from database
        $menuModel = new \App\Models\MenuModel();
        $this->data['dynamicMenus'] = $menuModel->getMenuTree();

        // E.g.: $this->session = service('session');

        // Ambil bahasa dari query string ?lang=id
        $lang = $request->getVar('lang');

        if ($lang && in_array($lang, config('App')->supportedLocales)) {
            service('request')->setLocale($lang);
            $this->locale = $lang;

            // Opsional → simpan di session
            session()->set('lang', $lang);
        } else {
            // Utamakan session
            $sessionLang = session()->get('lang');
            if ($sessionLang) {
                service('request')->setLocale($sessionLang);
                $this->locale = $sessionLang;
            } else {
                // default & browser detection
                $this->locale = service('request')->getLocale();
            }
        }
    }

    /**
     * This function will be used by your controllers to return JSON responses to the client.
     * Don�t forget to import the ResponseInterface. [use CodeIgniter\HTTP\ResponseInterface;]
     */
    public function getResponse(array $responseBody, int $code = ResponseInterface::HTTP_OK)
    {
        return $this
            ->response
            ->setStatusCode($code)
            ->setJSON($responseBody);
    }

    /**
     * Checks both fields in a request to get its content
     * Don�t forget to import the IncomingRequest class. [use CodeIgniter\HTTP\IncomingRequest;]
     */
    public function getRequestInput(IncomingRequest $request){
        $input = $request->getPost();
        if (empty($input)) {
            //convert request body to associative array
            $input = json_decode($request->getBody(), true);
        }
        return $input;
    }

    /**
     * Checks both fields in a request to get its content
     * Don�t forget to import the necessary classes. 
     * [use CodeIgniter\Validation\Exceptions\ValidationException;]
     * [use Config\Services;]
     */
    public function validateRequest($input, array $rules, array $messages =[]){
        $this->validator = Services::Validation()->setRules($rules);
        // If you replace the $rules array with the name of the group
        if (is_string($rules)) {
            $validation = config('Validation');
    
            // If the rule wasn't found in the \Config\Validation, we
            // should throw an exception so the developer can find it.
            if (!isset($validation->$rules)) {
                throw ValidationException::forRuleNotFound($rules);
            }
    
            // If no error message is defined, use the error message in the Config\Validation file
            if (!$messages) {
                $errorName = $rules . '_errors';
                $messages = $validation->$errorName ?? [];
            }
    
            $rules = $validation->$rules;
        }
        return $this->validator->setRules($rules, $messages)->run($input);
    }

    protected function addStyle($file) {
		$this->data['styles'][] = $file;
	}
	
	protected function addJs($file, $print = false) { //exit(dd($file));
		if ($print) {
			$this->data['scripts'][] = ['print' => true, 'script' => $file];
		} else {
			$this->data['scripts'][] = $file;
		}
	}

    /**
     * Sanitize input to ensure valid UTF-8 encoding.
     * Useful for handling data copied from non-UTF-8 sources (e.g., Excel, Word).
     *
     * @param mixed $input
     * @return mixed
     */
    protected function sanitizeUTF8($input)
    {
        if (is_string($input)) {
            // Check if the string is valid UTF-8
            if (!mb_check_encoding($input, 'UTF-8')) {
                // If not, try to convert it from ISO-8859-1 (common culprit) to UTF-8
                return mb_convert_encoding($input, 'UTF-8', 'ISO-8859-1');
            }
            return $input;
        }

        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = $this->sanitizeUTF8($value);
            }
            return $input;
        }

        return $input;
    }

    // New Render For Layout Custom Javascript/ Styled
    protected function render(string $view, array $data = [])
    {
        $data = array_merge($this->data, $data);
        echo view($view, $data);
    }
}
