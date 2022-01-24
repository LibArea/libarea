# Agouti site search module

Place the following code in Routes 

In main.php file:

```php
Route::type(['get', 'post'])->get('/search')->module('search', 'App\Search')->name('search');
```

radjax.php:

```php
Radjax\Route::get("/api-search/{?query}", ["post"], "Modules\Search\App\Search@api", ["protected" => true, "where" => ["query" => "[А-Яа-яa-zA-Z0-9]+"],  "session_saved" => false]);
```

This module is included in the Agouti site by default.