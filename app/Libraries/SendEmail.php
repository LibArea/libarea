<?php

use App\Models\User\{SettingModel, UserModel};
use JacksonJeans\Mail;
use JacksonJeans\MailException;

class SendEmail
{
    // https://github.com/JacksonJeans/php-mail
    public static function mailText($uid, $type, array $variables = [])
    {
        if (is_null($uid)) {
            return false;
        }

        $u_data = UserData::get();
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

        return true;
    }

    public static function send($email, $subject = '', $message = '')
    {
        if (config('general.smtp')) {
            $mail = new Mail('smtp', [
                'host'      => 'ssl://' . config('general.smtphost'),
                'port'      => config('general.smtpport'),
                'username'  => config('general.smtpuser'),
                'password'  => config('general.smtppass')
            ]);

            $mail->setFrom(config('general.smtpuser'))
                ->setTo($email)
                ->setSubject($subject)
                ->setHTML($message, true)
                ->send();
        } else {
            $mail = new Mail();
            $mail->setFrom(config('general.email'), config('meta.title'));

            $mail->to($email)
                ->setSubject($subject)
                ->setHTML($message, true)
                ->send();
        }
    }
}
