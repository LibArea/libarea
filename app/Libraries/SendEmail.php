<?php

use App\Models\User\{SettingModel, UserModel};
use JacksonJeans\Mail;
use JacksonJeans\MailException;

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
                    $link = Config::get('meta.url') . '/u/' . $user['user_login'] . '/notifications';
                    $message = Translate::get('you mentioned (@)') . ": \n" . $link . "\n\n" . Config::get('meta.url');
                    self::send($user['user_email'], Config::get('meta.name') . ' - ' . Translate::get('notification'), $message);
                }
            }
        }

        return true;
    }

    public static function send($email, $subject = '', $message = '')
    {
        if (Config::get('general.smtp')) {
            $mail = new Mail('smtp', [
                'host'      => 'ssl://' . Config::get('general.smtphost'),
                'port'      => Config::get('general.smtpport'),
                'username'  => Config::get('general.smtpuser'),
                'password'  => Config::get('general.smtppass')
            ]);

            $mail->setFrom(Config::get('general.smtpuser'))
                ->setTo($email)
                ->setSubject($subject)
                ->setText($message)
                ->send();
        } else {
            $mail = new Mail();
            $mail->setFrom(Config::get('general.email'), Config::get('meta.title'));

            $mail->to($email)
                ->setSubject($subject)
                ->setHTML($message, true)
                ->send();
        }
    }
}
