**ATTENTION:** you are on the README file of an unstable branch of Loriup specifically meant for the development of future releases. This means that the code on this branch is potentially unstable, and breaking change may happen without any prior notice. Do not use it in production environments or use it at your own risk! This is a test version, a training project!

# Loriup

Discussion (forum) and Q&A platform. Community based on PHP Micro-Framework HLEB.

## PHP Micro-Framework HLEB

https://github.com/phphleb/hleb

Routing > Controllers > Models > Page Builder > Debug Panel

A distinctive feature of the micro-framework HLEB is the minimalism of the code and the speed of work.

### Demonstration

![Loriup](https://raw.githubusercontent.com/Toxu-ru/AreaDev/main/public/assets/images/areadev.jpg)

![Loriup](https://raw.githubusercontent.com/Toxu-ru/AreaDev/main/public/assets/images/areadev2.jpg)

https://loriup.ru/

### For testing

*   Required: php 7*+, HTTPS 
*   Project root folder: public (configure the server )
*   database/dev.sql
*   settings: database/dbase.config.php and config.ini
*   Log in to your account using administrator credentials: `ss@sdf.ru` / `qwer14qwer14`
*   Or user: `test@test.ru` / `test@test.ru`

To change the translation, in the file: `start.hleb.php` find:

```php
define('SITE_LANG', 'ru' );
```

Edit:

```php
define('SITE_LANG', 'en' );
```

#### Ideas

Ideas, minimalism, design was taken from what I like:

*   https://news.ycombinator.com/
*   https://lobste.rs/
*   https://meta.discourse.org/
*   https://discuss.flarum.org/
*   https://tildes.net/
*   https://subreply.com/


...

#### MIT License

https://github.com/LoriUp/loriup/blob/main/LICENSE 