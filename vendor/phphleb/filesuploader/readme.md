### Загрузка файлов на PHP, JavaScript (полный цикл) через форму

Создание страницы/формы выбора изображений (вид по умолчанию)

```php
include "/vendor/phphleb/filesuploader/FilesUploader.php";
include "/vendor/phphleb/filesuploader/ImageUploader.php";

$form = new Phphleb\FilesUploader\ImageUploader(); // Класс загрузки изображений
$form->setMaxFiles(5); // Позволяет загружать до 5 файлов
$form->setFormAction("/fupload/"); // Адрес отправки данных
$form->createUploadPage(); // Создание формы загрузки после назначения параметров
```

Приём данных и сохранение

```php
$form = new Phphleb\FilesUploader\ImageUploader();
$form->setMaxFiles(5); // Позволяет загружать до 5 файлов (дублирование проверки на бекенде)
if(!$form->checkFilesAndCreateReport()){ // Проверка данных на соответствие
  die("Ошибка в данных");
}
$report = $form->copyFilesTo("/storage/public/temp/"); // Сохранение файлов в папку c получением отчёта
```

Есть возможность загружать произвольные файлы

```php
$form = new Phphleb\FilesUploader\FilesUploader(); // Класс загрузки файлов
$form->setFileExtensions([".txt", ".text", ".xml"]); // Перечень доступных расширений файлов
$form->setFileTypes((["text/plain", "text/xml"]); // Перечень доступных MIME-типов файлов
$form->setViewTypes(["TXT", "XML"]); // Перечень доступных расширений для отображения пользователю
$form->setMaxFiles(1); // Загрузка только одного файла
$form->setFormAction("/fupload/"); // Адрес отправки данных
$form->setMaxFileSize(300); // Максимальный размер файла в килобайтах (глобальное ограничение лучше устанавливать в настройках сервера)
$form->setLang("ru"); // Язык формы
$form->createUploadPage(); // Создание формы загрузки после назначения параметров (принимает необязательным параметром путь до файла с шаблоном формы по образцу /resources/page.php)
```

При приёме необходимо будет продублировать эти параметры






