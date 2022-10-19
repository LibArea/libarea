<?php

use App\Models\User\{SettingModel, UserModel};
use App\Exception\AutorizationException;

class SendEmail
{
    public static function mailText($uid, $type, array $variables = [])
    {
        if ($uid === null) {
            return false;
        }

        $user   = UserModel::getUser($uid, 'id');

        if ($type == 'appealed') {
            $setting = SettingModel::getNotifications($uid);
            $appealed = $setting['setting_email_appealed'] ?? 0;
            if ($appealed == 0) {
                return true;
            }
        }

        $text_footer    = __('mail.footer', ['name' => config('meta.url')]);
        $user_email     = $user['email'];
        $url            = config('meta.url');

        switch ($type) {
            case 'changing.password':
                $subject    = __('mail.changing_password_subject', ['name' => config('meta.name')]);
                $message    = __('mail.changing_password_message', ['url' => $url . $variables['newpass_link']]);
                break;
            case 'appealed':
                $subject    = __('mail.appealed_subject', ['name' => config('meta.name')]);
                $message    = __('mail.appealed_message', ['url' => $url . url('notifications')]);
                break;
            case 'activate.email':
                $subject    = __('mail.activate_email_subject', ['name' => config('meta.name')]);
                $message    = __('mail.activate_email_message', ['url' => $url . $variables['link']]);
                break;
            case 'invite.reg':
                $user_email = $variables['invitation_email'];
                $subject    = __('mail.invite_reg_subject', ['name' => config('meta.name')]);
                $message    = __('mail.invite_reg_message', ['url' => $url . $variables['link']]);
                break;
            default:
                $user_email = $variables['email'];
                $subject    = __('mail.test_subject', ['name' => config('meta.name')]);
                $message    = __('mail.test_message');
                break;
        }

        self::send($user_email, $subject, $message . $text_footer);
    }

    public static function send($email, $subject = '', $message = '')
    {
        if (config('integration.smtp')) {

            $mailSMTP = new SendMailSmtpClass(config('integration.smtp_user'), config('integration.smtp_pass'), 'ssl://' . config('integration.smtp_host'), config('integration.smtp_port'), "UTF-8");

            $from = array(
                config('meta.name'), // Имя отправителя
                config('integration.smtp_user') // почта отправителя
            );

            $result =  $mailSMTP->send($email, $subject, $message, $from);

            if ($result === true) {
                echo "Done";
            } else {
               throw AutorizationException::Smtp("Error - " . $result);
            }
        } else {
            $mail = new \Phphleb\Muller\StandardMail(false);
            $mail->setNameFrom(config('meta.name')); // вот тут было длинное
            $mail->setAddressFrom(config('general.email'));

            if (config('general.confirm_sender')) {
                $mail->setParameters('-f' . config('general.email'));
            }

            $mail->setTo($email);

            $mail->setTitle($subject);
            $mail->setContent($message);

            $mail->setDebug(true);
            $mail->setDebugPath(HLEB_GLOBAL_DIRECTORY . DIRECTORY_SEPARATOR . 'storage/logs');

            $mail->send();
        }

        return true;
    }
}
