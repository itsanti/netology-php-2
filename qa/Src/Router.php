<?php

namespace App;

class Router {

    const ROUTE_PARAM = 'r';

    protected $routes = [];
    protected $extentions;
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
        $this->extentions = (!empty($config['extensions'])) ? $config['extensions'] : [];
    }

    public function route($request)
    {
        $args = [];
        $path = rtrim($request->getRequestTarget(), '/');

        $rbody = $request->getBody();
        if (array_key_exists(self::ROUTE_PARAM, $rbody)) {
            $path .= '/' . $rbody[self::ROUTE_PARAM];
        }

        if (array_key_exists($path, $this->routes)) {
            $method = 'action' . $this->routes[$path];
        } else {
            $method = 'action404';
            $args = ['path' => $path];
        }
        if (method_exists('\\App\\Controllers\\Controller', $method)) {
            $ctrl = new Controllers\Controller($method);
            return call_user_func_array([$ctrl, $method], $args);
        }
        if (method_exists('\\App\\Controllers\\AdminController', $method)) {
            $ctrl = new Controllers\AdminController($method);
            if (array_key_exists('logger', $this->extentions)) {
                $class = '\\App\\Extensions\\Loggers\\' . $this->extentions['logger']['className'];
                $ctrl = new $class($ctrl, $this->extentions['logger']);
            }
            return call_user_func_array([$ctrl, $method], $args);
        }
        return 'Error';
    }

    public function getPath($name, $params = [])
    {
        return self::buildHref($name, $params);
    }

    public static function buildHref($route, $params = [])
    {
        if ($route == 'Index') {
            return self::$path_root . '/';
        }
        if (!empty($params)) {
            $params = http_build_query($params);
            return (self::$cleanUrl) ? self::$path_root . '/' . $route . '/?' . $params : '?'.self::ROUTE_PARAM.'=' . $route . '&' . $params;
        }
        return (self::$cleanUrl) ? self::$path_root . '/' . $route : '?'.self::ROUTE_PARAM.'=' . $route;
    }
}