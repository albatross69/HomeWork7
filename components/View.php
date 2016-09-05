<?php

require_once ROOT.'/vendor/autoload.php';

class View
{
    private $twig;

    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem(ROOT.'/view/templates');
        $twig = new Twig_Environment($loader);

        $this->twig = $twig;
    }

    public function generate($template, $data = null)
    {
        echo $this->twig->render($template, $data);
    }
}