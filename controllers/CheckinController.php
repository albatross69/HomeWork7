<?php
require_once ROOT.'/models/User.php';
require_once ROOT.'/models/Userfile.php';
require_once ROOT.'/vendor/autoload.php';
require_once ROOT.'/components/View.php';
require_once ROOT.'/components/Mailer.php';
require_once ROOT.'/components/Image.php';

class CheckinController
{
    public $user;
    public $userfile;
    public $view;

    public function actionIndex()
    {
        $this->view = new View();
        $this->view->generate('CheckinView.html', array());
    }

    public function actionReg()
    {
        //подключаемся к модели
        $this->user = new User();
        $this->userfile = new Userfile();

        //получаем данные из формы
        $username = trim(strip_tags($_POST['username']));
        $password = trim(strip_tags($_POST['password']));
        $name = trim(strip_tags($_POST['fullname']));
        $age = filter_var($_POST['age']);
        $about = trim(strip_tags($_POST['about']));
        $photoname = $_FILES['photo']['name'];
        $phototype = $_FILES['photo']['type'];
        $path_to_image = $_FILES['photo']['tmp_name'];
        $file = './photos/'.basename($photoname);

        //этот массив используется во взаимодействии с бд
        $input = array(
            'username' => $username,
            'password' => $password,
            'name' => $name,
            'age' => $age,
            'about' => $about,
            'Img' => $photoname,
            'ip' => $_SERVER['REMOTE_ADDR']
        );

        //проверяем, заполнены ли поля?
        if (empty($username) || empty($password) || empty($name) || empty($age) || empty($about) || empty($photoname))
        {
            echo 'Вы заполнили не все поля'."<a href='/checkin'>Попробуйте еще раз!</a>";
            exit;
        }

        //капча
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $key = '6Ld9YSkTAAAAAOt0jR0V6vOHX526Jh16YYHsN4oV';

        $response = file_get_contents($url."?secret=".$key."&response=".$_POST['g-recaptcha-response']);

        $response_data = json_decode($response, true);
        if (!isset($response_data['success']) or $response_data['success'] != 1)
        {
            echo 'Вы не прошли верификацию'."<a href='/checkin'>Попробуйте еще раз!</a>";
            exit;
        }

        //Валидация
        $gump = new GUMP();
        $gump->validation_rules(array(
                'name' => 'required|min_len,5',
                'about' => 'required|min_len,50',
                'ip' => 'valid_ip',
                'age' => 'numeric|min_numeric,10|max_numeric,100'
            )
        );

        $validated_data = $gump->run($input);

        if($validated_data === false)
        {
            echo $gump->get_readable_errors(true);
            echo "<br/><a href='/checkin'>Попробуйте еще раз!</a>";
            exit;
        }


        //имя пользователя
        if ($this->user->is_username_exist($username))
        {
            echo 'Пользователь с таким именем уже существует'."<a href='/checkin'>Попробуйте еще раз!</a>";
            exit;
        }

        //безопасность
        if (preg_match('/jpg|jpeg/', $photoname) or preg_match('/gif/', $photoname) or
            preg_match('/png/', $photoname))
        {
            if (preg_match('/jpg|jpeg/', $phototype) or preg_match('/gif/', $phototype) or
                preg_match('/png/', $phototype))
            {
                //если пользователь добавлен в бд
                if ($this->user->addUser($input) && $this->userfile->addtoUserfiles($input))
                {
                    //отправка письма
                    $mail = new Mailer();
                    if ($mail->reg_letter(true, 'Новый пользователь', "Пользователь <b>$username</b> зарегистрирован!"))
                    {
                        //Изменяем размер картинки
                        $img = new Image();
                        $img->resize($path_to_image, $photoname);
                        session_start();
                        $_SESSION['login'] = $username;
                        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
                        header('Location:'.$host.'user');
                    }
                    else
                    {
                        echo 'Что-то пошло не так';
                        exit;
                    }
                }
                else
                {
                    echo 'Что-то пошло не так';
                }
            }
            else
            {
                echo 'Неверный тип файла'."<a href='/checkin'>Попробуйте еще раз!</a>";
            }
        }
        else
        {
            echo 'Неверный тип файла'."<a href='/checkin'>Попробуйте еще раз!</a>";
        }


    }
}