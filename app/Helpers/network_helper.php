<?php

if (!function_exists('isWebsiteOnlineSocket')) {
    function isWebsiteOnlineSocket($url, $timeout = 5)
    {
        $parsed = parse_url($url);
        
        $scheme = $parsed['scheme'] ?? 'http';
        $host   = $parsed['host'] ?? $url;
        $port   = ($scheme === 'https') ? 443 : 80;

        $fp = @fsockopen(
            ($scheme === 'https' ? 'ssl://' : '') . $host,
            $port,
            $errno,
            $errstr,
            $timeout
        );

        if ($fp) {
            fclose($fp);
            return true;
        }
        return false;
    }
}

if (!function_exists('checkCdnAvailability')) {
    /**
     * Check if CDN (jsdelivr) is available
     * This function is optimized for quick checking during application startup
     * 
     * @return bool True if CDN is reachable, false otherwise
     */
    function checkCdnAvailability()
    {
        // CDN host to check
        $cdnHost = 'cdn.jsdelivr.net';
        $timeout = 2; // Quick timeout for startup check
        
        // Try to connect to CDN
        $fp = @fsockopen(
            'ssl://' . $cdnHost,
            443,
            $errno,
            $errstr,
            $timeout
        );

        if ($fp) {
            fclose($fp);
            return true;
        }
        
        // CDN is not reachable
        return false;
    }
}

if (!function_exists('asset_url')) {
    /**
     * Get asset URL (automatically uses CDN or local based on availability)
     * Usage in views: asset_url('css/style.css') or asset_url('js/script.js')
     * 
     * @param string $path Path to the asset (relative to asset base)
     * @return string Full URL to the asset
     */
    function asset_url(string $path = '', bool $isMinify = false): string
    {
        $appConfig = config('App');
        $baseUrl = $appConfig->getAssetUrl();
        
        // Remove leading slash from path if present
        $path = ltrim($path, '/');
        
        // Paksa minify di production
        if ($isMinify && ENVIRONMENT === 'production') {
            if (strpos($path, '.min.') === false) {
                $path = preg_replace('/(\.css|\.js)$/i', '.min$1', $path);
            }
        }
        
        // Improvement: Use base_url for local assets to support subfolder deployments
        if ($appConfig->isUsingLocalAssets()) {
            // Ensure URL helper is loaded
            if (! function_exists('base_url')) {
                helper('url');
            }
            return base_url($baseUrl . $path);
        }
        
        return $baseUrl . $path;
    }
}


