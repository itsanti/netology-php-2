<?php

namespace App;

class Router {

    protected $routes = [];
    protected static $cleanUrl = false;
    protected static $path_root = '';

    public function __construct($config)
    {
        $this->routes = array_map(function($a, $b) use ($config) {
            return [$config['path_root'] . $a => $b];
        }, array_keys($config['routes']), $config['routes']);
        $this->routes = call_user_func_array('array_merge', $this->routes);
        self::$cleanUrl = $config['clean_url'];
        self::$path_root = $config['path_root'];
    }

    public function route($request)
    {
        $path = rtrim($request->getRequestTarget(), '/');

        $rbody = $request->getBody();
        if (array_key_exists('r', $rbody)) {
            $path .= '/' . $rbody['r'];
        }

        if (array_key_exists($path, $this->routes)) {
            $method = 'action' . $this->routes[$path];
        } else {
            $method = 'action404';
        }
        if (method_exists('\\App\\Controller', $method)) {
            $ctrl = new Controller();
            return $ctrl->$method();
        }
        if (method_exists('\\App\\AdminController', $method)) {
            $ctrl = new AdminController($method);
            return $ctrl->$method();
        }
        return 'Error';
    }

    public function getPath($name)
    {
        if (self::$cleanUrl) {
            return array_search($name, $this->routes);
        }
        return self::buildHref($name);
    }

    public static function buildHref($route)
    {
        if ($route == 'Index') {
            return self::$path_root . '/';
        }
        return (self::$cleanUrl) ? $route : '?r=' . $route;
    }
}