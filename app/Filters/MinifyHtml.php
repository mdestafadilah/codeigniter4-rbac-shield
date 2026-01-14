<?php

// source: https://chatgpt.com/s/t_693133914ecc819191f427f4c0205244

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use voku\helper\HtmlMin;

class MinifyHtml implements FilterInterface
{
    protected $minifier;

    public function __construct()
    {
        $this->minifier = new HtmlMin();

        // Setting aman & kompatibel
        $this->minifier->doOptimizeViaHtmlDomParser(true);
        $this->minifier->doRemoveComments(true);
        $this->minifier->doRemoveEmptyAttributes(true);
        $this->minifier->doRemoveHttpPrefixFromAttributes(true);
        $this->minifier->doRemoveDeprecatedAnchorName(true);
        $this->minifier->doRemoveDeprecatedTypeFromScriptTag(true);
        $this->minifier->doRemoveDeprecatedTypeFromStylesheetLink(true);
        $this->minifier->doSortCssClassNames(true);
        $this->minifier->doSortHtmlAttributes(true);
        $this->minifier->doRemoveSpacesBetweenTags(true);
    }

    public function before(RequestInterface $request, $arguments = null)
    {
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Jalankan hanya di production
        if (ENVIRONMENT !== 'production') {
            return;
        }

        // Hanya untuk HTML
        $contentType = $response->getHeaderLine('Content-Type');
        if (stripos($contentType, 'text/html') === false) {
            return;
        }

        $output = $response->getBody();

        if (!empty($output)) {
            $output = $this->minifier->minify($output);
            $response->setBody($output);
        }
    }
}
