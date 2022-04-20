# Demo



A small demo of this search engine is available [here](https://midnight-cms.com/search.php).
This demo is made with a dataset of 1000 movies from 2006 and 2016. The results are provided as you type.

# Installation

install this library via Composer :

```
composer require vfou/php-search 1.1

composer install --ignore-platform-reqs
```

# What can it do ?

in short :
- indexing and searching documents (with score, fuzzy search and tokenization)
- Stemming and stop-words of 12 supported languages
- Faceting
- Autocompletion
- Connex Search

Take a look at the [Feature Page](https://github.com/VincentFoulon80/php-search/wiki/Features) for a more complete listing

# Quick start

The search engine is packaged with an example schema that allow you to take hand quickly on the library.

at first you need to load the search engine.

```php
use VFou\Search\Engine;

$engine = new Engine();
```

You can give an array in parameter of the class constructor, see [the wiki's configuration page](https://github.com/VincentFoulon80/php-search/wiki/Configuration) for more informations.

By constructing the engine, there'll be some directory that appeared next to your index file :
- var/engine/index
- var/engine/documents
- var/engine/cache

(All these directories can be changed with the configuration array)

At first, you have to give to the engine something to search for. We'll create some documents and ask the engine to index them.

```php
$doc = [
    "id" => 1,
    "type" => "example-post",
    "title" => "this is my first blog post !",
    "content" => "I am very happy to post this first post in my blog !",
    "categories" => [
        "party",
        "misc."
    ],
    "date" => "2018/01/01",
    "comments" => [
        [
            "author" => "vincent",
            "date" => "2018/01/01",
            "message" => "Hello world!"
        ],
        [
            "author" => "someone",
            "date" => "2018/01/02",
            "message" => "Welcome !"
        ]
    ]
];
$engine->update($doc);
$doc = [
    "id" => 2,
    "type" => "example-post",
    "title" => "This is the second blog post",
    "content" => "a second one for fun",
    "date" => "2018/01/05",
    "categories" => [
        "misc."
    ],
    "comments" => [
        [
            "author" => "someone",
            "date" => "2018/01/05",
            "message" => "Another one ?!"
        ]
    ]
];
$engine->update($doc);
```

Note : you can also put these two documents in one array and use the updateMultiple() function for indexing multiple documents at once.

Now that you documents are indexed, you can use the search function and fetch results :

```php
$response = $engine->search('second post');
var_dump($response);

$response = $engine->search('post');
var_dump($response);
```

For more informations about this library, like using more advanced features, go to [the wiki page of this repository](https://github.com/VincentFoulon80/php-search/wiki)

# Admin Panel

:warning: **Warning : This panel does not handle any security by itself. If you use it, it's up to you to prevent the public from accessing it !**

The Admin panel is a class that need to be instantiated and then run. It's not a callable file so you'll need to call it via a regular php file :

```php
<?php

use VFou\Search\AdminPanel;

// include the composer autoload file, modify the path if needed
require "vendor/autoload.php";

// securize your file access or directly here
// if($_SERVER['REMOTE_ADDR'] != "127.0.0.1") exit('unauthorized');

// instantiate the panel and then run it
$admin = new AdminPanel();
echo $admin->run();
```

the AdminPanel's constructor accept as first parameter the same config array as you may use to instanciate the Engine, and you'll want to pass it if you have customized schemas. (or else the panel will not work properly)

More informations in the [Admin Panel Manual](https://github.com/VincentFoulon80/php-search/wiki/Admin-Panel-Manual)

# License

This library is under the MIT license. [See the complete license](LICENSE)


