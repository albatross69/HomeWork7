<?php
require_once ROOT.'/models/UserModel.php';
class LoginController
{
    public $model;

    public function actionIndex()
    {
        require_once(ROOT.'/view/LoginView.php');
    }

    public function actionAuthorize()
    {
        $this->model = new UserModel();
        $username = trim(strip_tags($_POST['username']));
        $password = trim(strip_tags($_POST['password']));

        if ($this->model->is_password_right($username, $password))
        {
            session_start();
            $_SESSION['login'] = $username;
            $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
            header('Location:'.$host.'user');
        }
        else
        {
            echo 'Неправильное имя пользователя или пароль'."<a href='/login'>Попробуйте еще раз!</a>";
        }
    }
}
