<?php

namespace JacksonJeans;

/**
 * MailException
 * @category    Class
 * @package     GIDUTEX
 * @author      Julian Tietz <julian.tietz@gidutex.de>
 * @license     Julian Tietz <julian.tietz@gidutex.de>
 * @link        https://gidutex.de
 * @version     1.0
 */
class MailException extends \Exception
{

    const CODE_UNKNOWN_ERROR = 0x01;
    const CODE_SMTP_CONNECTION_FAILED = 0x02;
    const CODE_SMTP_SOCKET_WRITE_ERROR = 0x03;
    const CODE_SMTP_SOCKET_ERROR = 0x04;
    const CODE_SMTP_UNKNOWN_ERROR = 0x05;
    const CODE_SMTP_ERROR = 0x06;
    const CODE_INVALID_ARGUMENT = 0x07;


    /**
     * @var array Vordefinierte Fehlermeldung
     */
    private static $errorMessages = array(
        self::CODE_SMTP_CONNECTION_FAILED => 'Die Verbindung konnte nicht eingerichtet werden. "\'%s\'".',
        self::CODE_UNKNOWN_ERROR => 'Unbekannter Error: Die Meldung lautet: "\'%s\'".',
        self::CODE_SMTP_UNKNOWN_ERROR => 'Unbekannter Error des SMTP Servers: "\'%s\'".',
        self::CODE_SMTP_SOCKET_WRITE_ERROR => 'Mail konnte aufrund eines SOCKET_WRITE_ERRORS nicht geschrieben werden.',
        self::CODE_SMTP_ERROR => 'Ein SMTP-Error ist aufgetreten: "\'%s\'".',
        self::CODE_INVALID_ARGUMENT => 'Ungültiges Argument: "\'%s\'".'
    );

    /**
     * Neue SplittingException erstellen, fügt automatisch eine aussagekräftige Fehlermeldung hinzu, wenn der Fehlercode bekannt ist.
     *
     * @param int $code
     * - Error Code
     * @param string $splittingSubject 
     * - Die Zeichenfolge, die zu spalten versucht wurde
     */
    public function __construct($code, $splittingSubject = '')
    {
        if (!array_key_exists($code, self::$errorMessages)) {
            $code = self::CODE_UNKNOWN_ERROR;
        }
        $message = sprintf(self::$errorMessages[$code], $splittingSubject);

        parent::__construct($message, $code);
    }
}
