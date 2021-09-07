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

    const PARAM_PRIVATE         = "parameters.private";

    const PARAM_SEARCH          = "parameters.search";

    const PARAM_SITE_OFF        = "parameters.siteoff";

    const PARAM_EMAIL           = "parameters.email";
    const PARAM_SMTP            = "parameters.smtp";
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

    const PARAM_TL_ADD_SPACE    = "parameters.tl_add_space";
    const PARAM_TL_ADD_PM       = "parameters.tl_add_pm";
    const PARAM_TL_ADD_COMM_QA  = "parameters.tl_add_comm_qa";
    const PARAM_TL_ADD_POST     = "parameters.tl_add_post";
    const PARAM_TL_ADD_COMM     = "parameters.tl_add_comm";
    const PARAM_TL_ADD_URL      = "parameters.tl_add_url";

    private static $data = null;

    public static function get(string $name): string
    {
        if (is_null(self::$data)) {
            self::$data = parse_ini_file(HLEB_GLOBAL_DIRECTORY . '/config.ini');
        }
        return self::$data[$name];
    }
}