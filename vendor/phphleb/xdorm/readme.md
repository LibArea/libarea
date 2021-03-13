XD::ORM
=====================
 #### XD ORM for PHP Micro-Framework HLEB
 If you need to install the framework, use the link: [github.com/phphleb/hleb](https://github.com/phphleb/hleb) 
 
 #### HLEB
 
 A distinctive feature of the micro-framework HLEB is the minimalism of the code and the speed of work. The choice of this framework allows you to launch a full-fledged product with minimal time costs and appeals to documentation; it is easy, simple and fast.
 At the same time, it solves typical tasks, such as routing, shifting actions to controllers, model support, so, the basic MVC implementation. This is the very minimum you need to quickly launch an application.

 #### XD ORM
 
In fact, XD ORM is a shell over PHP PDO, designed specifically for the framework HLEB. It is simple and corresponds to the general purpose of the micro framework.


The XD ORM is not included in the original configuration of the framework, so it must be copied to the folder with the vendor/phphleb  libraries from the [github.com/phphleb/xdorm](https://github.com/phphleb/xdorm)  repository or installed using Composer:

```bash
$ composer require phphleb/xdorm
```

Connection to the project:

```php
// File /app/Optional/MainConnect.php
... [
"XdORM\XD" => "vendor/phphleb/xdorm/XD.php"
] ...
```


You can create queries in basic SQL style for the selected DBMS (any, supported by PDO). The main task implemented in this ORM is to turn the query string into a secure query string.

With knowledge of the SQL syntax, you can start writing the Model immediately. This syntax is interpreted as follows for commands:

```php
use XdORM\XD;

XD::any(); // 'ANY'
XD::any_words(); // 'ANY_WORDS'
XD::anyWords(); // 'ANY WORDS'
XD::any()->words(); // 'ANY WORDS'
```

For table names, it is enough to pass them in an array:

```php
$query = XD::select(['id', 'name', 'email'])->from(['users']);
 // SELECT `id`, `name`, `email` FROM `users`;
```

Values are inserted as is, but all string values, except for SQL statements and special characters, will be checked using the built-in PDO:

```php
$query = XD::select('*')->from(['users'])->where(['name'], '!=', "d'Artanyan")->and(['id'], '=', 1)->limit(1);
// SELECT * FROM `users` WHERE `name` != 'd\'Artanyan' AND `id` = 1 LIMIT 1;

$query = XD::select('*')->from(['users'])->where(['id'])->in('(', 15, ',', 43, ',', 60, ',', 71, ')');
// SELECT * FROM `users` WHERE `id` IN ( 15, 43, 60, 71);
```


To pass an array of values, there is a special method setList():

```php
$ids = [15, 43, 60, 71];
$query = XD::select('*')->from(['users'])->where(['id'])->in('(', XD::setList($ids), ',', 156, ',', 200, ')');
// SELECT * FROM `users` WHERE `id` IN ( 15, 43, 60, 71, 156, 200);
```

The connection of the parts of the query can be made between the returned objects XD in any order:

```php
$select_user_id = XD::select(['id'])->from(['users'])->where(['id'], '=', 15);
$query = XD::select('*')->from(['tasks'])->where(['user_id'], '=', '(', $select_user_id, ')');
```

Or so:

```php
$q = XD::select('*')->from(['users']);

$query = $q->limit(100);
// SELECT * FROM `users` LIMIT 100;

// or (but not the "and", as there will be a concatenation with the previous action)

$query = $q->leftJoin(['tasks'])->on(['users.id'], '=', ['tasks.user_id']);
// SELECT * FROM `users` LEFT JOIN `tasks` ON `users`.`id` = `tasks`.`user_id`;
```

Now you need to run this query and get a result if it is implied. For queries with return data, the following methods exist:

```php
$result = $query->getSelectOne(); // Getting one row in the named array.
$result = $query->getSelectValue(); // Getting one value.
$result = $query->getSelect(); // Returns an array of rows as named arrays.
$result = $query->getSelectAll(); // Returns an array of objects whose values can be obtained by the fields of the objects.
```

For all other queries that do not return a result set, it is enough to add run() to execute the query.

```php
XD::update(['users'])->set(['name'], '=', 'admin')->where(['id'], '=', 1)->run();

XD::dropTable(['users'])->run();
```

In exceptional cases, you can use the execute() method for queries, which returns a PDOStatement object, and take further actions with the latter according to the PDO documentation.

#### Using multiple connections

The required file "dbase.config.php" does not initially exist and must be copied to the "database" directory from the file "database/default.dbase.config.php". If the XD ORM is not connected to the HLEB framework, then the full path to the configuration file may be specified using the constant XDDB_PATH_TO_CONFIG (the file template is located in the “templates” folder).

A configuration file may contain various variants of database connections; by default, the specified connection name is used from the HLEB_TYPE_DB constant. To execute a query to another of the databases specified in the configuration file, you must specify its name in the query execution method used. For example: run('postgresql.first'), getSelectOne('postgresql.second') or getSelect('mysql.name'). The query will be executed with the specified settings. 

 
 
 
 
