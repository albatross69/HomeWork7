<?php
require_once ROOT.'/vendor/autoload.php';
/*
 * Работа с изображениями
 * */
class Image extends Intervention\Image\ImageManagerStatic
{
    /*
     * Метод зменяет размер картинки и сохраняет ее в папку с фото
     *
     * @param string $path_to_image, $imgname
     * @return void
     * */
    public function resize($path_to_image, $imgname)
    {
        $image = Image::make($path_to_image)->resize(480, 480)->save(ROOT.'/photos/'.$imgname, 100);
    }
}