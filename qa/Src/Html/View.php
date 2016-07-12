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
    }

    public function render($tpl, $vars)
    {
        return $this->twig->render($tpl, $vars);
    }

}