<?php
class UserModel
{
    protected $mysql;
    function __construct()
    {
        $this->mysql = new mysqli('localhost', 'root', '', 'HomeWork4');
        if ($this->mysql->errno)
        {
            die($this->mysql->error);
        }
        $this->mysql->query('SET NAMES UTF-8');
    }
    //Проверяет, существует ли пользователь с данным юзернеймом
    public function is_username_exist($username)
    {
        $select = "SELECT username FROM `users`";
        $result = $this->mysql->query($select);
        $logins = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($logins as $key => $logarr)
        {
            foreach ($logarr as $login)
            {
                if ($username == $login)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
    }
    //Добавляет данные о пользователе, переданные при регистрации
    public function addUser($input)
    {
        $query = "INSERT INTO users(`username`, `password`, `name`, `age`, `about`, `Img`) VALUES (?,?,?,?,?,?)";
        $adduser = $this->mysql->prepare($query);
        if ($adduser->bind_param('sssiss', $input['username'], $input['password'], $input['name'], $input['age'],
            $input['about'], $input['Img']))
        {
            $adduser->execute();
            return true;
        }
    }
    public function addtoUserfiles($input)
    {
        $query = "INSERT INTO userfiles(`username`, `Img`) VALUES (?,?)";
        $addinfo = $this->mysql->prepare($query);
        if ($addinfo->bind_param('ss', $input['username'], $input['Img']))
        {
            $addinfo->execute();
            return true;
        }
    }
    //Проверяет правильность введенного пароля
    public function is_password_right($username, $password)
    {
        $query = "SELECT password FROM `users` WHERE username = '$username'";
        $result = $this->mysql->query($query);
        $user_data = $result->fetch_all(MYSQLI_ASSOC);
        if (empty($user_data[0]['password']))
        {
            return false;
        }
        else
        {
            if ($user_data[0]['password'] == $password)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    //Возвращает данные о пользователе
    public function getUsers()
    {
        $query = "SELECT username, name, age, about FROM `users`";
        $result = $this->mysql->query($query);
        $userlist = $result->fetch_all(MYSQLI_ASSOC);
        return $userlist;
    }
    //Добавление картинки в таблицу userfiles
    public function addImg($username, $imgname)
    {
        $query = "INSERT INTO userfiles(`username`, `Img`) VALUES (?,?)";
        $addimg = $this->mysql->prepare($query);
        if ($addimg->bind_param('ss', $username, $imgname))
        {
            $addimg->execute();
            return true;
        }
        return false;
    }
    //Возвращает список файлов, загруженных пользователем
    public function getFiles($username)
    {
        $query = "SELECT Img FROM `userfiles` WHERE username = '$username'";
        $res = $this->mysql->query($query);
        $filelist = $res->fetch_all(MYSQLI_ASSOC);
        return $filelist;
    }
}