<?php

require_once 'config.php';
/*
 * Данный класс содержит методы для работы стаблицей userfiles
 *
 * */
class Userfile extends Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;

    /*
     * Добавляет данные пользователя, переданные при регистрации
     *
     * @param array $input
     * @return bool
     * */
    public function addtoUserfiles($input)
    {
        $userfile = new Userfile();
        $userfile->username = $input['username'];
        $userfile->Img = $input['Img'];
        if ($userfile->save())
        {
            return true;
        }
    }

    /*
     * Добавление картинки в таблицу userfiles
     *
     * @param string $username $imgname
     * @return bool
     * */
    public function addImg($username, $imgname)
    {
        $userfile = new Userfile();
        $userfile->username = $username;
        $userfile->Img = $imgname;
        if ($userfile->save())
        {
            return true;
        }
    }

    /*
     * Возвращает список файлов, загруженных пользователем
     *
     * @param string $username
     * @return array $filelist
     * */
    public function getFiles($username)
    {
        $filelist = Userfile::select('Img')
            ->where('username', '=', $username)
            ->get()
            ->toArray();
        return $filelist;
    }
}