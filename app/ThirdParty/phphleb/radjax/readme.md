 ## Radjax (fast Ajax- and API-router)
 
 ### ![RADJAX LOGO](https://raw.githubusercontent.com/phphleb/radjax/671f5116685362cfb733e94e665d7ca5efb15821/Src/logo.jpg)

The Radjax is not included in the original configuration of the framework [HLEB](https://github.com/phphleb/hleb), so it must be copied to the folder with the vendor/phphleb  libraries from the [github.com/phphleb/radjax](https://github.com/phphleb/radjax)  repository or installed using Composer:

```bash
$ composer require phphleb/radjax
```

Connection to the project in /routes/radjax.php (are priority)

```php

Radjax\Route::get("/info/", ["get"], "App\Controllers\TestController@index", ["protected"=>false]);

// and advanced customization

Radjax\Route::get("/weather/{y}/{m}/{d}/{h?}/", ["get","post"], "App\Controllers\TestController@weather", ["protected"=>true, "where"=>["y"=>"[0-9]+", "m"=>"[0-9]+", "d"=>"[0-9]+", "h"=>"[0-9]+"], "session_saved" => false]);

```

Connection separate from HLEB :

```php
// require or through classes autoloader

require '/vendor-directory/phphleb/radjax/Route.php';

require '/vendor-directory/phphleb/radjax/Src/RCreator.php';

require '/vendor-directory/phphleb/radjax/Src/App.php';

// Initialization with the path to the route file.
// If the route was found, boolean `true` will be returned, else `false`.
$isActive = (new Radjax\Src\App(['/path-to-directory/routes/radjax-route.php']))->get();

```
