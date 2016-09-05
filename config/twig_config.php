<?php
    require_once ROOT.'/vendor/autoload.php';

$loader = new Twig_Loader_Filesystem(ROOT.'/view/templates');
$twig = new Twig_Environment($loader);