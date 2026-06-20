<?php

class App {
    protected $controller = 'Auth';
    protected $method = 'login';
    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();

        // Check controller file
        if (isset($url[0])) {
            if (file_exists(dirname(__DIR__, 2) . '/app/controllers/' . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }

        require_once dirname(__DIR__, 2) . '/app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // Run controller & method, and send params if any
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }

        // Fallback for Vercel or other environments without URL rewrite query param
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            $path = explode('?', $requestUri)[0];
            
            // Strip project base subdirectory if present (e.g. on local XAMPP)
            $scriptName = $_SERVER['SCRIPT_NAME'];
            $scriptDir = dirname($scriptName);
            $scriptDir = str_replace('\\', '/', $scriptDir);
            
            if ($scriptDir === '/api') {
                $scriptDir = '';
            } else if (substr($scriptDir, -4) === '/api') {
                $scriptDir = substr($scriptDir, 0, -4);
            }
            
            if ($scriptDir !== '/' && !empty($scriptDir)) {
                if (strpos($path, $scriptDir) === 0) {
                    $path = substr($path, strlen($scriptDir));
                }
            }
            
            $path = trim($path, '/');
            if ($path !== '') {
                $url = filter_var($path, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                return $url;
            }
        }

        return [];
    }
}
