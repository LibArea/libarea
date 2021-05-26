<?php

namespace Lori;

class Config
{
    // Название запрашиваемого значения
    const PARAM_NAME            = "parameters.name";
    const PARAM_HOME_TITLE      = "parameters.hometitle";
    const PARAM_META_DESC       = "parameters.metadesc";
    const PARAM_BANNER_TITLE    = "parameters.bannertitle";
    const PARAM_BANNER_DESC     = "parameters.bannerdesc";
    
    const PARAM_URL             = "parameters.url";
    const PARAM_MODE            = "parameters.mode";
    const PARAM_INVITE          = "parameters.invite";
    
    const PARAM_SEARCH          = "parameters.search";

    const PARAM_SITE_OFF        = "parameters.siteoff";
    const PARAM_OFF_TEXT        = "parameters.offtext";

    const PARAM_SMTP_USER       = "parameters.smtpuser";
    const PARAM_SMTP_PASS       = "parameters.smtppass";
    const PARAM_SMTP_HOST       = "parameters.smtphost";
    const PARAM_SMTP_POST       = "parameters.smtpport";
 
    const PARAM_CAPTCHA         = "parameters.captcha"; 
    const PARAM_PUBLIC_KEY      = "parameters.public_key";
    const PARAM_PRICATE_KEY     = "parameters.private_key";

    const PARAM_DISCORD         = "parameters.discord";
    const PARAM_WEBHOOK_URL     = "parameters.webhook_url";
    const PARAM_NAME_BOT        = "parameters.name_bot";
    const PARAM_ICON_URL        = "parameters.icon_url";

    const PARAM_SPACE           = "parameters.space";
    const PARAM_PM_MAIL         = "parameters.pm_mail";

    private static $data = null;

    public static function get(string $name): string
    {
        if (is_null(self::$data)) {
            self::$data = parse_ini_file(CONFIG_FILE_PATH);
        }
        return self::$data[$name];
    }
}