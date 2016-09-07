<?php
require_once 'config.php';
/*
 * Данный класс содержит методы для работы с таблицей users
 *
 * */
class User extends Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;

    /*
     * Проверяет, существует ли пользователь с данным username
     *
     * @param string $username
     * @return bool
     * */
    public function is_username_exist($username)
    {
        $user = User::where('username', '=', $username)
            ->get()
            ->toArray();
        if (!empty($user))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
     * Добавляет данные о пользователе, переданные при регистрации
     *
     * @param array $input
     * @return bool
     * */
    public function addUser($input)
    {
        $user = new User();

        $user->username = $input['username'];
        $user->password = $input['password'];
        $user->name = $input['name'];
        $user->age = $input['age'];
        $user->about = $input['about'];
        $user->Img = $input['Img'];
        $user->ip = $input['ip'];
        if ($user->save())
        {
            return true;
        }
    }

    /*
     * Проверяет пароль, введенный пользователем
     *
     * @param string $username, $password
     * @return bool
     * */
    public function is_password_right($username, $password)
    {
        $user = User::where('username', '=', $username)
            ->get()
            ->toArray();
        if ($user[0]['password'] == $password)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
     * Возвращает данные о пользователе
     *
     * @param
     * @return array $userlist
     * */
    public function getUsers()
    {
        $userlist = User::select('username', 'name', 'age', 'about')
            ->get()
            ->toArray();
        return $userlist;
    }
}