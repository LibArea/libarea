## php-url-record
> A simple slug generator. It's a PHP port from [nopCommerce](https://github.com/nopSolutions/nopCommerce) UrlRecord service.

## Why php-url-record?
The reason behind this repository is nopCommerce, is an open source ASP.NET Core based ecommerce solution. I just wanted to create a port of the nopCommerce SEO friendly slug generation service which is in production for years and trusted by tens of thousands of stores.

## Compatibility

PHP 7 >= 7.4.0 required due to use of built-in [mb_str_split](https://www.php.net/manual/en/function.mb-str-split.php) function.

## Installation

`composer require hsynlms/url-record`

## Usage

```php
use hsynlms\UrlRecord;

$slugGenerator = new UrlRecord();
echo $slugGenerator->GetSeoFriendlyName('nobodY d0es_it better');
// will return -> nobody-d0es_it-better
```

## Options

| Name              | Type               | Default                             | Description                                                                                                          |
| ---               | ---                | ---                                 | ---                                                                                                                  |
| name         | string | -                                | The string that will be slugified  |
| convertNonWesternChars         | boolean            | true                                | A value indicating whether non western chars should be converted  |
| allowUnicodeCharsInUrls  | boolean            | false                               | A value indicating whether Unicode chars are allowed  |

## Contribution
Contributions and pull requests are kindly welcomed!

## License
This project is licensed under the terms of the [MIT license](https://github.com/hsynlms/php-url-record/blob/master/LICENSE).
