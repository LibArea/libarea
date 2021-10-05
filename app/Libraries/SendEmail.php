<?php

namespace Agouti;

use App\Models\User\SettingModel;
use JacksonJeans\Mail;
use JacksonJeans\MailException;
use Agouti\Config;

class SendEmail
{
    // https://github.com/JacksonJeans/php-mail
    public static function mailText($user_id, $type)
    {
        // TODO: Let's check the e-mail at the mention
        if ($type == 'appealed') {
            $setting = SettingModel::getNotifications($user_id);
            if ($setting) {
                if ($setting['setting_email_appealed'] == 1) {
                    $user = UserModel::getUser($user_id, 'id');
                    $link = 'https://' . HLEB_MAIN_DOMAIN . '/u/' . $user['user_login'] . '/notifications';
                    $message = lang('You were mentioned (@), see') . ": \n" . $link . "\n\n" . HLEB_MAIN_DOMAIN;
                    self::send($user['user_email'], Config::get(Config::PARAM_NAME) . ' - ' . lang('notification'), $message);
                }
            }
        }

        return true;
    }

    public static function send($email, $subject = '', $message = '')
    {
        if (Config::get(Config::PARAM_SMTP) == 1) {
            $mail = new Mail('smtp', [
                'host'      => 'ssl://' . Config::get(Config::PARAM_SMTP_HOST),
                'port'      => Config::get(Config::PARAM_SMTP_POST),
                'username'  => Config::get(Config::PARAM_SMTP_USER),
                'password'  => Config::get(Config::PARAM_SMTP_PASS)
            ]);

            $mail->setFrom(Config::get(Config::PARAM_SMTP_USER))
                ->setTo($email)
                ->setSubject($subject)
                ->setText($message)
                ->send();
       } else {
            $mail = new Mail();
            $mail->setFrom(Config::get(Config::PARAM_EMAIL), Config::get(Config::PARAM_HOME_TITLE));
             
            $mail->to($email)
                ->setSubject($subject)
                ->setHTML($message, true)
                ->send();
       }
    }
}
