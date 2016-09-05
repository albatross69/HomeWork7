<?php
    require_once ROOT.'/models/UserModel.php';
    require_once ROOT.'/components/View.php';
class UserController
{
    public $model;
    public $view;

    public function actionIndex()
    {
        session_start();
        if (empty($_SESSION['login']))
        {
            echo 'Эта страница доступна только зарегистрированным пользователям'."<a href=\"/login\">Вход</a>";
        }
        else
        {
            $this->view = new View();
            $this->view->generate('UserView.html', array());
        }
    }

    public function actionUserlist()
    {
        session_start();
        if (empty($_SESSION['login']))
        {
            echo 'Эта страница доступна только зарегистрированным пользователям'."<a href=\"/login\">Вход</a>";
        }
        else {

            echo 'Список пользователей' . "<br/>";

            $this->model = new UserModel();
            $data = $this->model->getUsers();

            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['age'] >= 18) {
                    $data[$i]['adult'] = 'Совершеннолетний';
                } else {
                    $data[$i]['adult'] = 'Несовершеннолетний';
                }
            }
            $this->view = new View();

            $this->view->generate('UserlistView.html', array('data' => $data));
        }
    }

    public function actionLoad()
    {
        session_start();
        if (empty($_SESSION['login']))
        {
            echo 'Эта страница доступна только зарегистрированным пользователям'."<a href=\"/login\">Вход</a>";
        }
        else
        {
            $this->model = new UserModel();
            $pictname = $_FILES['photo']['name'];
            $picttype = $_FILES['photo']['type'];
            $file = './photos/'.basename($pictname);

            if (preg_match('/jpg|jpeg/', $pictname) or preg_match('/gif/', $pictname) or
                preg_match('/png/', $pictname))
            {
                if (preg_match('/jpg|jpeg/', $picttype) or preg_match('/gif/', $picttype) or
                    preg_match('/png/', $picttype))
                {
                    if ($this->model->addImg($_SESSION['login'], $pictname))
                    {
                        copy($_FILES['photo']['tmp_name'], $file);
                        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
                        header('Location:'.$host.'user');
                    }
                    else
                    {
                        echo 'Не удалось добавить картинку'."<a href=\"/user\">Назад</a>";
                    }
                }
                else
                {
                    echo 'Неверный тип файла'."<a href=\"/user\">Назад</a>";
                }

            }
            else
            {
                echo 'Неверный тип файла'."<a href=\"/user\">Назад</a>";
            }
        }
    }

    public function actionFilelist()
    {
        session_start();
        if (empty($_SESSION['login']))
        {
            echo 'Эта страница доступна только зарегистрированным пользователям'."<a href=\"/login\">Вход</a>";
        }
        else
        {
            echo "Список файлов, загруженных вами: <br/>";

            $this->model = new UserModel();
            $data = $this->model->getFiles($_SESSION['login']);

            $this->view = new View();

            $this->view->generate('UserfileView.html', array('data' => $data));
        }
    }

    public function actionQuit()
    {
        if ($_GET['exit'] == 1)
        {
            session_start();
            session_destroy();
            $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
            header('Location:'.$host);
        }
    }
}