<?php

namespace App;

class Router {

    protected $routes = [];
    protected static $cleanUrl = false;

    public function __construct($config)
    {
        $this->routes = array_map(function($a, $b) use ($config) {
            return [$config['path_root'] . $a => $b];
        }, array_keys($config['routes']), $config['routes']);
        $this->routes = call_user_func_array('array_merge', $this->routes);
        self::$cleanUrl = $config['clean_url'];
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
        return 'Error';
    }

    public function getPath($name)
    {
        return array_search($name, $this->routes);
    }

    public static function buildHref($route)
    {
        return (self::$cleanUrl) ? $route : '?r=' . $route;
    }
}