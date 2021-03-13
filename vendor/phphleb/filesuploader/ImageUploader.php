<?php

namespace Phphleb\FilesUploader;


class ImageUploader extends FilesUploader
{
    protected $file_extensions = [".jpg", ".jpeg", ".png"]; // Перечень доступных расширений файлов
    protected $file_types = ['image/jpeg','image/pjpeg','image/png']; // Перечень доступных MIME-типов файлов
    protected $view_types = ["PNG", "JPG"]; // Перечень доступных типов для отображения пользователю
    protected $form_action = null; // // На этот же адрес или указать путь отправки файлов
    protected $max_file_size = 1048576*2; // 2 MB (Максимальный размер файла в KB)
    protected $max_files = 3; // Максимальное кол-во файлов за раз (при активном multiple)
    protected $multiple = true; // Мультизагрузка файлов
    protected $lang = "ru"; // Язык страницы
    protected $image_object = true; // Выводить картинки превью
}

