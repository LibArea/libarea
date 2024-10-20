<?php

declare(strict_types=1);

use App\Models\User\{SettingModel, UserModel};
use App\Exception\AutorizationException;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendEmail
{
    public static function mailText($uid, $type, array $variables = [])
    {
        if ($uid === null) {
            return false;
        }

        $user   = UserModel::get($uid, 'id');

        if ($type === 'appealed') {
            $setting = SettingModel::getNotifications($uid);
            $appealed = $setting['setting_email_appealed'] ?? 0;
            if ($appealed == 0) {
                return true;
            }
        }

        $lang = $user['lang'] ?? config('general', 'lang');
        Translate::setLang($lang);

        $text_footer    = __('mail.footer', ['name' => config('meta', 'url')]);
        $user_email     = $user['email'];
        $url            = config('meta', 'url');

        switch ($type) {
            case 'changing.password':
                $subject    = __('mail.changing_password_subject', ['name' => config('meta', 'name')]);
                $message    = __('mail.changing_password_message', ['url' => $url . $variables['newpass_link']]);
                break;
            case 'appealed':
                $subject    = __('mail.appealed_subject', ['name' => config('meta', 'name')]);
                $message    = __('mail.appealed_message', ['url' => $url . url('notifications')]);
                break;
            case 'activate.email':
                $subject    = __('mail.activate_email_subject', ['name' => config('meta', 'name')]);
                $message    = __('mail.activate_email_message', ['url' => $url . $variables['link']]);
                break;
            case 'new.email':
                $user_email = $variables['new_email'];
                $subject    = __('mail.new_email_subject', ['name' => config('meta', 'name')]);
                $message    = __('mail.new_email_message', ['url' => $url . $variables['link']]);
                break;
            case 'invite.reg':
                $user_email = $variables['invitation_email'];
                $subject    = __('mail.invite_reg_subject', ['name' => config('meta', 'name')]);
                $message    = __('mail.invite_reg_message', ['url' => $url . $variables['link']]);
                break;
            default:
                $user_email = $variables['email'];
                $subject    = __('mail.test_subject', ['name' => config('meta', 'name')]);
                $message    = __('mail.test_message');
                break;
        }

        self::send($user_email, $subject, $message . $text_footer);

        return true;
    }

    public static function send($email, $subject = '', $message = '')
    {
        if (config('integration', 'smtp')) {

            $mail = new PHPMailer(true);

            try {
                // Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = config('integration', 'smtp_host');        //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = config('integration', 'smtp_user');        //SMTP username
                $mail->Password   = config('integration', 'smtp_pass');        //SMTP password
                $mail->SMTPSecure = 'ssl'; // PHPMailer::ENCRYPTION_SMTPS;  //Enable implicit TLS encryption
                $mail->Port       = config('integration', 'smtp_port');        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                /* $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ]; */

                $mail->CharSet    = 'utf-8';

                //Recipients
                $mail->setFrom(config('integration', 'smtp_user'), config('meta', 'name'));
                $mail->addAddress($email);                                  //Name is optional

                //Content
                $mail->isHTML(true);                                        //Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $message;

                $mail->send();
                return true;
            } catch (Exception $e) {
                throw AutorizationException::Smtp("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
        } else {
            $mail = new \Phphleb\Muller\StandardMail(false);
            $mail->setNameFrom(config('meta', 'name')); // вот тут было длинное
            $mail->setAddressFrom(config('general', 'email'));

            if (config('general', 'confirm_sender')) {
                $mail->setParameters('-f' . config('general', 'email'));
            }

            $mail->setTo($email);

            $mail->setTitle($subject);
            $mail->setContent($message);

            $mail->setDebug(true);
            $mail->setDebugPath(HLEB_GLOBAL_DIR . DIRECTORY_SEPARATOR . 'storage/logs');

            $mail->send();
        }

        return true;
    }
}
