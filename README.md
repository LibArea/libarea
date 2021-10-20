<p align="center"><a href="https://agouti.ru" target="_blank"><img src="https://agouti.ru/assets/svg/agouti.svg" width="120"></a></p>

# Agouti

Discussion (forum) and Q&A platform. Community based on PHP Micro-Framework HLEB.

## Ideas

We like the classification system based on labels (tags), how it works in the example: [Stack Exchange](https://stackoverflow.com/), [Zhihu](https://www.zhihu.com/), [Quora](https://www.quora.com/) and a number of others.

We are trying to study this.

![Agouti](https://raw.githubusercontent.com/AgoutiDev/agouti/main/public/assets/images/agouti.jpg)

![Agouti spaces](https://raw.githubusercontent.com/AgoutiDev/agouti/main/public/assets/images/agouti2.jpg)

![Agouti topics](https://raw.githubusercontent.com/AgoutiDev/agouti/main/public/assets/images/agouti3.jpg)

Demo: https://agouti.ru/

### For testing

*   Project root folder: public (configure the server )
*   dev.sql
*   settings: config/dbase.config.php and other files in the directory
*   Log in to your account using administrator credentials: `ss@sdf.ru` / `qwer14qwer14`
*   Or user: `test@test.ru` / `test@test.ru`

PHP 7 >= 7.4.0 required due to use of built-in [mb_str_split](https://www.php.net/manual/en/function.mb-str-split.php) function.

More information (rus.): https://agouti.info/

...

#### PHP Micro-Framework HLEB

https://github.com/phphleb/hleb

Routing > Controllers > Models > Page Builder > Debug Panel

A distinctive feature of the micro-framework HLEB is the minimalism of the code and the speed of work.

#### MIT License

https://github.com/AgoutiDev/agouti/blob/main/LICENSE 

---

**ATTENTION:** you are on the README file of an unstable branch of Agouti specifically meant for the development of future releases. This means that the code on this branch is potentially unstable, and breaking change may happen without any prior notice. Do not use it in production environments or use it at your own risk! This is a test version, a training project!