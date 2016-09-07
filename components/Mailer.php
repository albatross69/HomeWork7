<?php
require_once ROOT.'/vendor/autoload.php';
/**
 * Работа с почтой
 */
class Mailer
{
    /*
     * Отправляет письмо при регистрации
     *
     * @param bool $is_html string $subject, $body
     * @return bool
     * */
    public function reg_letter($is_html, $subject, $body)
    {
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.yandex.ru';
        $mail->SMTPAuth = true;
        $mail->Username = 'doe.senior';
        $mail->Password = 'qwerty123456';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('doe.senior@yandex.ru', 'dz06.loftschool');
        $mail->addAddress('ar.abelyan@yandex.ru');
        $mail->isHTML($is_html);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


        if(!$mail->send())
        {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        else
        {
            return true;
        }
    }
}