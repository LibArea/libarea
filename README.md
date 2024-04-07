<p align="center">
<a href="https://github.com/LibArea/libarea/blob/main/LICENSE"><img src="https://img.shields.io/badge/License-MIT%20(Free)-brightgreen.svg" alt="License: MIT"></a>
<img src="https://img.shields.io/badge/PHP-8.2-blue" alt="PHP">
</p>

# LibArea

A platform for collective blogs and social media platform, forum, question and answer service. Catalog of sites (programs), site navigation and directories - facets.

## Ideas

We like the classification system based on labels (tags), how it works in the example: [Stack Exchange](https://stackoverflow.com/), [Zhihu](https://www.zhihu.com/), [Quora](https://www.quora.com/) and a number of others.

We are trying to study this.

![LibArea](https://raw.githubusercontent.com/LibArea/libarea/main/public/assets/images/libarea1.jpg)

![LibArea spaces](https://raw.githubusercontent.com/LibArea/libarea/main/public/assets/images/libarea2.jpg)

Second design (Q&A) in the system: 

![LibArea topics](https://raw.githubusercontent.com/LibArea/libarea/main/public/assets/images/libarea3.jpg)

Directory of sites (programs) uses a faceted scheme:

![LibArea catalog](https://raw.githubusercontent.com/LibArea/libarea/main/public/assets/images/catalog.jpg)

Site-based test design (https://lobste.rs/)

![LibArea test design](https://raw.githubusercontent.com/LibArea/libarea/main/public/assets/images/libarea-test.jpg)

**Demo:** https://libarea.ru/

### For testing (Installation)

Install the package through [Composer](http://getcomposer.org/). 

Further:

*   migrate: scripts/dev.sql
*   settings: config/database.php and other files in the directory
*   Log in to your account using administrator credentials: `ss@sdf.ru` / `qwer14qwer14`
*   Or user: `test@test.ru` / `test@test.ru`

PHP >= 8.2, MySQL 8+ or > MariaDB 10.2.2


**The Public Directory**

The `public` directory contains the `index.php` file, which is the entry point for all requests entering your application and configures autoloading. This directory also houses your assets such as images, JavaScript, and CSS.

---

**ATTENTION:** you are on the README file of an unstable branch of LibArea specifically meant for the development of future releases. This means that the code on this branch is potentially unstable, and breaking change may happen without any prior notice. Do not use it in production environments or use it at your own risk! This is a test version, a training project!
