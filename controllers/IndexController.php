<?php

require_once ROOT.'/components/View.php';
class IndexController
{
    public $view;
    public function actionIndex()
    {
        $this->view = new View();
        $this->view->generate('Main.html', array());
    }
}