<?php

namespace App\Html;

class View {
    
    private $twig = null;

    public function __construct($conf)
    {
        $loader = new \Twig_Loader_Filesystem(APP_ROOT . $conf['templates']);
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => !empty($conf['cache']) ? APP_ROOT . $conf['cache'] : false
        ));
        $filter = new \Twig_SimpleFilter('buildHref', array('\\App\\Router', 'buildHref'));
        $this->twig->addFilter($filter);
    }

    public function addGlobal($var, $value)
    {
        $this->twig->addGlobal($var, $value);
    }

    public function render($tpl, $vars)
    {
        return $this->twig->render($tpl, $vars);
    }
}