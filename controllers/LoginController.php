<?php
require_once ROOT.'/models/User.php';
class LoginController
{
    public $user;

    public function actionIndex()
    {
        require_once(ROOT.'/view/LoginView.php');
    }

    public function actionAuthorize()
    {
        $this->user = new User();
        $username = trim(strip_tags($_POST['username']));
        $password = trim(strip_tags($_POST['password']));

        if ($this->user->is_password_right($username, $password))
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
