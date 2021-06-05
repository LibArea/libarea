<?php

namespace JacksonJeans;

/**
 * Mail
 * @category    Class
 * @package     GIDUTEX
 * @author      Julian Tietz <julian.tietz@gidutex.de>
 * @license     Julian Tietz <julian.tietz@gidutex.de>
 * @link        https://gidutex.de
 * @version     1.0
 */
class Mail
{
    /**
     * @var string $toName
     */
    protected $toName;

    /**
     * @var string $toEmail
     */
    protected $toEmail;

    /**
     * @var string $fromName
     */
    protected $fromName;

    /**
     * @var string $fromEmail
     */
    protected $fromEmail;

    /**
     * @var string $replyName
     */
    protected $replyName;

    /**
     * @var string $replyEmail
     */
    protected $replyEmail;

    /**
     * @var string $subject 
     */
    protected $subject;

    /**
     * @var string $text 
     */
    protected $text;

    /**
     * @var string $html 
     */
    protected $html;

    /**
     * @var array $attachments
     */
    protected $attachments;

    /**
     * @var string $priority
     */
    protected $priority;

    /**
     * @var array $customHeaders
     */
    protected $customHeaders;

    /**
     * @var string|callable
     */
    protected $transport;

    /**
     * @var array $transportParams
     */
    protected $transportParams;

    /**
     * Konstanten
     * Priorität der EMail die gesendet wird.
     * @var string PRIORITY_
     */
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_URGENT = 'urgent';
    const PRIORITY_NON_URGENT = 'non-urgent';

    /**
     * Konstruktor
     *
     * @param string|callable $transport 
     * - intern unterstützt "mail" und "smtp"
     * @param array $transportParams 
     * - für "mail", für "smtp" üntersützte Parameter
     * - ['host' => 'localhost','port' => 25, 'username' => '', 'password' => '', 'timeout' => 5 ]
     */
    public function __construct($transport = 'mail', array $transportParams = [])
    {
        $this->toName = $this->toEmail = $this->fromName = $this->fromEmail = $this->replyName = $this->replyEmail = $this->subject = $this->text = $this->html = $this->priority = '';
        $this->attachments  = $this->customHeaders = [];
        $this->setTransport($transport, $transportParams);
        return $this;
    }

    /**
     *  method to create a Mail instance with current mail settings
     *
     * @param string|array $email
     * @param string $name
     *
     * @return Mail 
     */
    public function to($email, $name = '')
    {
        $mail          = clone $this;
        $mail->toEmail = $email;
        $mail->toName  = $name;

        return $mail;
    }

    /**
     * Alternative Methode, um Mail zu instanziieren mit im Objekt bereits vorhandener Konfiguration
     *
     * @param string $to 
     * - Empfänger E Mail Adresse
     * @param string $subject 
     * - Betreff
     * @param string $message 
     * - Nachricht
     *
     * @return Mail
     */
    public function compose($to = '', $subject = '', $message = '')
    {
        $mail          = clone $this;
        $mail->toEmail = $to;
        $mail->subject = $subject;
        $mail->text    = $message;

        return $mail;
    }

    /**
     * Setzte Empfänger E Mail Adresse
     * 
     * @param string|array $email 
     * @param string $name 
     *
     * @return Mail $this
     */
    public function setTo($email, $name = '')
    {
        $this->toEmail = $email;
        $this->toName  = $name;

        return $this;
    }

    public function getToEmail()
    {
        return $this->toEmail;
    }

    public function getToName()
    {
        return $this->toName;
    }

    /**
     * Setzte Versender-Email und Namen
     * 
     * @param string $email 
     * - EMail des Addressaten
     * @param string $name 
     * - Name des E Mail Addressaten
     * 
     * @return Mail $this
     */
    public function setFrom($email, $name = '')
    {
        $this->fromEmail = $email;
        $this->fromName  = $name;

        return $this;
    }

    /**
     * Erhalte Versender EMail
     * 
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * Erhalte Versender Name
     * 
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Setzte Antwort-An-Empfänger-Attribute
     * 
     * @param string|array $email 
     * - Email Adressen
     * @param string $name 
     * - Name des E Mail Addressaten
     * 
     * @return Mail $this
     */
    public function setReplyTo($email, $name = false)
    {
        $this->replyEmail = $email;
        $this->replyName  = $name;

        return $this;
    }

    /**
     * Erhalte Antwort-An-Empfänger-Attribute [Email]
     * 
     * @return string
     */
    public function getReplyToEmail()
    {
        return $this->replyEmail;
    }

    /**
     * Erhalte Antwort-An-Empfänger-Attribute [Name]
     * 
     * @return string
     */
    public function getReplyToName()
    {
        return $this->replyName;
    }

    /**
     * Erhalte den Betreff
     * 
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Setze Betreff 
     * @param string $subject 
     * - Betreff
     * @return Mail $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Erhalte Text-Nachricht
     * 
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /** 
     * Setzte Text Nachricht
     * 
     * @param string $text 
     * - Nachricht die übermittelt werden soll.
     * @return Mail $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Füge Text-Nachricht hinzu
     * 
     * @param string $text [required]
     * - Nachricht die übermittelt werden soll.
     * @return Mail $this
     */
    public function addText($text)
    {
        $this->text .= ($this->text === '' ? '' : "\r\n") . preg_replace('/\r\n?|\n/', "\r\n", $text);

        return $this;
    }

    /**
     * Erhalte HTML-Nachricht
     */
    public function getHTML()
    {
        return $this->html;
    }

    /**
     * Legt Nachricht als HTML fest.
     * 
     * @param string $html
     * - HTML Nachricht
     * @param bool $addAltText [default: <bool>false]
     * - Text auch als AltText hinzufügen, falls Empfänger-Client keine HTML Mails unterstützt
     * @return Mail $this
     */
    public function setHTML($html, $addAltText = false)
    {
        $this->html = $html;
        if ($addAltText) {
            $this->text = strip_tags($this->html);
        }

        return $this;
    }

    /**
     * Erhalte die gesetze Prio
     * 
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param $priority string Can be 'normal', 'urgent', or 'non-urgent'
     * - Mögliche Werte:
     * -- normal
     * -- urgent
     * -- non-urgent
     *
     * @return Mail $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Füge einen Anhang an
     * 
     * @param string $attachment [required]
     * - Pfad zur Datei.
     * @param string $inlineFileName 
     * - Datei in entsprechende Zeichenkette für den Versand umbennen.
     * @return Mail $this
     */
    public function attach($attachment, $inlineFileName = '')
    {
        $this->attachments[$inlineFileName ?: basename($attachment)] = $attachment;

        return $this;
    }

    /**
     * Erhalte die Anhänge
     * 
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Erhalte den CustomHeader
     * 
     * @return array
     */
    public function getCustomHeaders()
    {
        return $this->customHeaders;
    }

    /**
     * Setter für CustomHeaders
     * @param array $ch
     * - Array mit Werten für den CustomHeader
     * 
     * @return Mail $this
     */
    public function setCustomHeaders(array $ch)
    {
        $this->customHeaders = $ch;

        return $this;
    }

    /**
     * Erhalte den Transport
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Lege den Transport fest
     * @param string|callable $transport 
     * - Transport Methode als mail|smtp oder eigene Funktion mittels callable
     * @param array $transportParams
     * - Transport Parameter
     * 
     * @return Mail $this
     */
    public function setTransport($transport, array $transportParams = [])
    {
        if ($transport === 'mail') {
            $this->transport = 'mail';
            $this->transportParams = $transportParams;
        } else if ($transport === 'smtp') {
            $this->transport       = 'smtp';
            $this->transportParams = array_merge([
                'host'     => 'localhost',
                'port'     => 25,
                'username' => '',
                'password' => '',
                'timeout'  => 5
            ], $transportParams);
        } else if (is_callable($transport)) {
            $this->transport       = $transport;
            $this->transportParams = $transportParams;
        } else {
            throw new MailException(MailException::CODE_INVALID_ARGUMENT, 'Invalid transport (mail, smtp or callable supported).');
        }

        return $this;
    }

    /**
     * Erhalte Parameter für den SMTP-Transport
     * @return array
     */
    public function getTransportParams()
    {
        return $this->transportParams;
    }

    /**
     * toArray Methode
     * Gibt das Objekt als Array aus, um es irgendwo zwischen zu speichern.
     * 
     * @return array
     */
    public function toArray()
    {
        return array(
            'toName'      => $this->toName,
            'toEmail'     => $this->toEmail,
            'fromName'    => $this->fromName,
            'fromEmail'   => $this->fromEmail,
            'replyName'   => $this->replyName,
            'replyEmail'  => $this->replyEmail,
            'subject'     => $this->subject,
            'text'        => $this->text,
            'html'        => $this->html,
            'attachments' => $this->attachments,
            'priority'    => $this->priority,
            'customHeaders' => $this->customHeaders
        );
    }

    /**
     * fromArray Methode
     * Instanziiert das Objekt aus einem Array heraus.
     * 
     * @param array $a 
     * - Array
     * 
     * @return Mail $mail
     */
    public function fromArray(array $array)
    {
        $mail = clone $this;
        foreach ($array as $key => $value) {
            if (property_exists($mail, $key)) {
                $mail->{$key} = $value;
            }
        }
        return $mail;
    }

    /**
     * toJSON Methode
     * Gibt das Objekt als JSON String aus, um es irgendwo zwischen zu speichern.
     * 
     * @return string 
     */
    public function toJSON()
    {
        return json_encode($this->toArray());
    }

    /**
     * fromJson Methode
     * Instanziiert das Objekt aus einem JSON String gheraus.
     */
    public function fromJSON($json)
    {
        $mail = clone $this;
        if ($json = json_decode($json)) {
            foreach ($json as $key => $value) {
                if (property_exists($mail, $key)) {
                    $mail->{$key} = $value;
                }
            }
        }
        return $mail;
    }

    /**
     * Sendet die zusammengestellte Mail mit dem ausgewählten Transport.
     * @return bool
     */
    public function send()
    {

        $result = false;

        # Validierung
        if (!$this->toEmail) {
            throw new MailException(MailException::CODE_INVALID_ARGUMENT, 'E-Mail to required!');
        }
        if (!$this->fromEmail) {
            throw new MailException(MailException::CODE_INVALID_ARGUMENT, 'E-Mail from required!');
        }
        if (!$this->subject) {
            throw new MailException(MailException::CODE_INVALID_ARGUMENT, 'E-Mail subject required!');
        }
        if (!$this->text && !$this->html) {
            throw new MailException(MailException::CODE_INVALID_ARGUMENT, 'E-Mail message required!');
        }
        if ($this->priority && !in_array($this->priority, [self::PRIORITY_NORMAL, self::PRIORITY_URGENT, self::PRIORITY_NON_URGENT], true)) {
            throw new MailException(MailException::CODE_INVALID_ARGUMENT, "Priority possible values 'normal', 'urgent' or 'non-urgent'");
        }

        # Zeilenumbruch nach OS festlegen.
        if (strtoupper(0 === strpos(PHP_OS, 'WIN'))) {
            $EOL = "\r\n";
        } elseif (strtoupper(0 === strpos(PHP_OS, 'MAC'))) {
            $EOL        = "\r";
            $this->text = str_replace("\r\n", "\r", $this->text);
            $this->html = str_replace("\r\n", "\r", $this->html);
        } else {
            $EOL        = "\n";
            $this->text = str_replace("\r\n", "\n", $this->text);
            $this->html = str_replace("\r\n", "\n", $this->html);
        }

        # [JT] hier die einzelnen header festlegen

        # Mailer
        $headers = 'X-Mailer: JacksonJeansMailPHP/' . PHP_VERSION . $EOL;

        # validiere toEmail Adresse string
        if (is_array($this->toEmail)) {
            $to = implode(', ', $this->toEmail);
        } else if ($this->toName) {
            if (preg_match('/^[a-zA-Z0-9\-\. ]+$/', $this->toName)) {
                $to = $this->toName . ' <' . $this->toEmail . '>';
            } else {
                $to = '=?UTF-8?B?' . base64_encode($this->toName) . '?= <' . $this->toEmail . '>';
            }
        } else {
            $to = $this->toEmail;
        }

        $toHeader = 'To: ' . $to . $EOL;

        # validiere subject string
        if (preg_match('/^[a-zA-Z0-9\-\. ]+$/', $this->subject)) {
            $subject = $this->subject;
        } else {
            $subject = '=?UTF-8?B?' . base64_encode($this->subject) . '?=';
        }

        $subjectHeader = 'Subject: ' . $subject . $EOL;
        $headers .= 'MIME-Version: 1.0' . $EOL;

        $from = $this->fromEmail;
        if ($this->fromName) {
            if (preg_match('/^[a-zA-Z0-9\-\. ]+$/', $this->fromName)) {
                $from = $this->fromName . ' <' . $from . '>';
            } else {
                $from = '=?UTF-8?B?' . base64_encode($this->fromName) . '?= <' . $from . '>';
            }
        }

        $headers .= 'From: ' . $from . $EOL;

        $replyTo = $from;
        if ($this->replyEmail) {
            $replyTo = $this->replyEmail;
            if ($this->replyName) {
                if (preg_match('/^[a-zA-Z0-9\-\. ]+$/', $this->replyName)) {
                    $replyTo = $this->replyName . ' <' . $replyTo . '>';
                } else {
                    $replyTo = '=?UTF-8?B?' . base64_encode($this->replyName) . '?= <' . $replyTo . '>';
                }
            }
        }

        $headers .= 'Reply-To: ' . $replyTo . $EOL;
        $headers .= 'Date: ' . gmdate('D, d M Y H:i:s O') . $EOL;

        if ($this->priority) {
            $headers .= 'Priority: ' . $this->priority . $EOL;
        }

        # füge custom header hinzu.
        if (count($this->customHeaders)) {
            foreach ($this->customHeaders as $k => $v) {
                $headers .= $k . ': ' . $v . $EOL;
            }
        }

        # initialisiere message var und lege type fest.
        $message = '';
        $type = ($this->html && $this->text) ? 'alt' : 'plain';
        $type .= count($this->attachments) ? '_attachments' : '';

        # Stelle Nachricht zusammen nach Typ der Mail.
        switch ($type) {
                # plain
            case 'plain':
                $headers .= 'Content-Type: ' . ($this->html ? 'text/html' : 'text/plain') . '; charset="UTF-8"';
                $message .= $this->html ?: $this->text;
                break;
                # alt
            case 'alt':
                $boundary = md5(uniqid(time(), true));

                $headers .= 'Content-Type: multipart/alternative; format=flowed; delsp=yes; boundary="' . $boundary . '"';

                $message .= '--' . $boundary . $EOL;
                $message .= 'Content-Type: text/plain; charset="UTF-8"' . $EOL;
                $message .= 'Content-Transfer-Encoding: base64' . $EOL . $EOL;
                $message .= chunk_split(base64_encode($this->text), 76, $EOL);
                $message .= $EOL . '--' . $boundary . $EOL;
                $message .= 'Content-Type: text/html; charset="UTF-8"' . $EOL;
                $message .= 'Content-Transfer-Encoding: base64' . $EOL . $EOL;
                $message .= chunk_split(base64_encode($this->html), 76, $EOL);
                $message .= $EOL . '--' . $boundary . '--';
                break;
                # plain mit anhang
            case 'plain_attachments':
                $boundary = md5(uniqid(time(), true));

                $headers .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"';

                $message .= '--' . $boundary . $EOL;
                if ($this->text) {
                    $message .= 'Content-Type: text/plain; charset="UTF-8"' . $EOL;
                    $message .= 'Content-Transfer-Encoding: base64' . $EOL . $EOL;
                    $message .= chunk_split(base64_encode($this->text), 76, $EOL);
                } else {
                    $message .= 'Content-Type: text/html; charset="UTF-8"' . $EOL;
                    $message .= 'Content-Transfer-Encoding: base64' . $EOL . $EOL;
                    $message .= chunk_split(base64_encode($this->html), 76, $EOL);
                }
                foreach ($this->attachments as $basename => $fullname) {
                    $content = file_get_contents($fullname);
                    $message .= $EOL . '--' . $boundary . $EOL;
                    $message .= 'Content-Type: application/octetstream' . $EOL;
                    $message .= 'Content-Transfer-Encoding: base64' . $EOL;
                    $message .= 'Content-Disposition: attachment; filename="' . $basename . '"' . $EOL;
                    $message .= 'Content-ID: <' . $basename . '>' . $EOL . $EOL;
                    $message .= chunk_split(base64_encode($content), 76, $EOL);
                }
                $message .= $EOL . '--' . $boundary . '--';
                break;
                # alt mit anhang
            case 'alt_attachments':
                $boundary  = md5(uniqid(time(), true));
                $boundary2 = 'bd2_' . $boundary;

                $headers .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"';

                $message .= '--' . $boundary . $EOL;
                $message .= 'Content-Type: multipart/alternative; boundary="' . $boundary2 . '"' . $EOL . $EOL;
                $message .= '--' . $boundary2 . $EOL;
                $message .= 'Content-Type: text/plain; charset="UTF-8"' . $EOL;
                $message .= 'Content-Transfer-Encoding: base64' . $EOL . $EOL;
                $message .= chunk_split(base64_encode($this->text), 76, $EOL);
                $message .= $EOL . '--' . $boundary2 . $EOL;
                $message .= 'Content-Type: text/html; charset="UTF-8"' . $EOL;
                $message .= 'Content-Transfer-Encoding: base64' . $EOL . $EOL;
                $message .= chunk_split(base64_encode($this->html), 76, $EOL);
                $message .= $EOL . '--' . $boundary2 . '--';

                foreach ($this->attachments as $basename => $fullname) {
                    $content = file_get_contents($fullname);
                    $message .= $EOL . '--' . $boundary . $EOL;
                    $message .= 'Content-Type: application/octetstream' . $EOL;
                    $message .= 'Content-Transfer-Encoding: base64' . $EOL;
                    $message .= 'Content-Disposition: attachment; filename="' . $basename . '"' . $EOL;
                    $message .= 'Content-ID: <' . $basename . '>' . $EOL . $EOL;
                    $message .= chunk_split(base64_encode($content), 76, $EOL);
                }
                $message .= $EOL . '--' . $boundary . '--';
        }

        # Mail
        if ($this->transport === 'mail') {

            ini_set('sendmail_from', $this->fromEmail);
            $params = sprintf('-f %s -r %s', $this->fromEmail, $this->replyEmail ?: $this->fromEmail);
            foreach ($this->transportParams as $k => $v) {
                $params .= ' ' . $k . ' ' . $v;
            }
            $result = mail($to, $subject, $message, $headers, $params);

            # SMTP
        } else if ($this->transport === 'smtp') {
            $headers = $toHeader . $subjectHeader . $headers;

            $context = stream_context_create([
                'ssl' => array(
                    'verify_peer'      => false,
                    'verify_peer_name' => false
                )
            ]);

            $remote_socket = strpos($this->transportParams['host'], 'unix:') === 0 ? $this->transportParams['host'] : $this->transportParams['host'] . ':' . $this->transportParams['port'];

            # erstelle SMTP Server Verbindung her
            $fp = stream_socket_client($remote_socket, $errno, $errstr, $this->transportParams['timeout'], STREAM_CLIENT_CONNECT, $context);

            if ($fp) {

                if (!empty($_SERVER['SERVER_NAME'])) {
                    $server = $_SERVER['SERVER_NAME'];
                } else {
                    list(, $server) = explode('@', $this->fromEmail);
                }

                $lines = ['HELO ' . $server];
                if (!empty($this->transportParams['username'])) {
                    $lines[] = 'AUTH LOGIN';
                    $lines[] = base64_encode($this->transportParams['username']);
                    $lines[] = base64_encode($this->transportParams['password']);
                }
                $lines[] = 'MAIL FROM: <' . $this->fromEmail . '>';

                $recipients = is_array($this->toEmail) ? $this->toEmail : [$this->toEmail];
                foreach ($recipients as $recipient) {
                    $lines[] = 'RCPT TO: <' . $recipient . '>';
                }
                $lines[] = 'DATA';
                $lines[] = $headers . $EOL . $EOL . $message . $EOL . '.';
                $lines[] = 'QUIT';
                $sent    = 0;
                foreach ($lines as $line) {
                    $data = '';
                    $s    = '';
                    while (is_resource($fp) && !feof($fp) && substr($s, 3, 1) !== ' ') {
                        $s = fgets($fp, 1024);
                        if ($s === false) {
                            throw new MailException(MailException::CODE_SMTP_CONNECTION_FAILED);
                        }

                        $data .= $s;
                    }

                    if (strpos($data, '5') === 0) {
                        throw new MailException(MailException::CODE_SMTP_UNKNOWN_ERROR, $data);
                    }

                    if (!fwrite($fp, $line . $EOL)) {
                        throw new MailException(MailException::CODE_SMTP_SOCKET_WRITE_ERROR);
                    }

                    $sent++;
                }
                if ($sent === count($lines)) {
                    $result = true;
                }

                fclose($fp);
            } else {
                throw new MailException(MailException::CODE_SMTP_ERROR, $errstr);
            }
        } else if (is_callable($this->transport)) {

            $headers = $toHeader . $subjectHeader . $headers;
            $encoded = [
                'from'    => $from,
                'to'      => $to,
                'subject' => $subject,
                'message' => $message,
                'headers' => $headers
            ];
            $result  = call_user_func($this->transport, $this, $encoded);
        }

        return $result;
    }
}
