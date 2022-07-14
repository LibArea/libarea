<?php

use App\Models\User\{SettingModel, UserModel};

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
        $mail = new PHPMailer();

        if (config('integration.smtp')) {

            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->CharSet    = "utf-8";
                $mail->Host       = config('integration.smtp_host');
                $mail->SMTPAuth   = true;
                $mail->Username   = config('integration.smtp_user');
                $mail->Password   = config('integration.smtp_pass');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = config('integration.smtp_port');

                //Recipients
                $mail->setFrom(config('integration.smtp_user'), config('meta.name'));
                $mail->addAddress($email, '');

                //Content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $message;

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $mail->isSendmail();
            $mail->CharSet = "utf-8";
            $mail->setFrom(config('general.email'), config('meta.title'));
            $mail->addAddress($email, '');
            $mail->Subject = $subject;
            $mail->msgHTML($message);

            //send the message, check for errors
            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        }
        return true;
    }
}
