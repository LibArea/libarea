<?php
/**
 * @author  Foma Tuturov <fomiash@yandex.ru>
 */

declare(strict_types=1);

namespace Phphleb\FilesUploader;


class FilesUploader
{
    protected $file_extensions;  // Перечень доступных расширений файлов
    protected $file_types;       // Перечень доступных MIME-типов файлов
    protected $view_types;       // Перечень доступных типов для отображения пользователю
    protected $form_action;      // На этот же адрес или указать адрес отправки файлов
    protected $max_file_size;    // Максимальный размер файла в KB
    protected $max_files;        // Максимальное кол-во файлов за раз (при активном multiple)
    protected $multiple = false; // Мультизагрузка файлов
    protected $lang = "en";      // Язык страницы
    private $trans;
    private $trans_name;
    protected $image_object = false;

    protected $files_data;
    protected $files_all_data;
    protected $report;

    public function __construct()
    {
        $this->files_data = $_SERVER["REQUEST_METHOD"] == "POST" ? $_FILES["image_uploads"] ?? [] : [];
    }

    public function setFileExtensions(array $extensions)
    {
        $this->file_extensions = $extensions;
    }

    public function setFileTypes(array $types)
    {
        $this->file_types = $types;
    }

    public function setViewTypes(array $types)
    {
        $this->view_types = $types;
    }

    public function setFormAction(string $path)
    {
        $this->form_action = $path;
    }

    public function setMaxFileSize(int $KB)
    {
        $this->max_file_size = $KB;
    }

    public function setMaxFiles(int $quantity)
    {
        if($quantity > 1) $this->setMultiple();

        $this->max_files = $quantity;
    }

    private function setMultiple()
    {
        $this->multiple = true;
    }

    public function setLang(string $lg)
    {
        $this->lang = $lg;
    }

    public function getTrans(string $name)
    {
        if (empty($this->trans)) {
            $lang = [];
            include "trans/" . $this->lang . ".php";
            $this->trans = $lang;
        }
        return $this->trans[$name];
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function getImageObject()
    {
        return $this->image_object ? 1 : 0;
    }

    public function getViewsTypes()
    {
        return $this->view_types;
    }

    public function getMaxFileSize()
    {
        return $this->max_file_size;
    }

    public function getFileTypes()
    {
        return $this->file_types;
    }

    public function getFormAction()
    {
        return $this->form_action;
    }

    public function getFileExtensions()
    {
        return $this->file_extensions;
    }

    public function getMaxFiles()
    {
        return $this->max_files;
    }

    public function getMultiple()
    {
        return $this->multiple;
    }

    public function getVariableFileName()
    {
        return $this->multiple ? $this->getTransName("файлы") : $this->getTransName("файл");
    }

    public function getReplacedFileName()
    {
        return $this->multiple ? $this->getTransName("файлов") : $this->getTransName("файла");
    }

    protected function getTransName( string $name)
    {
        if(empty($this->trans_name)) {
            $lang = [];
            include "trans/languages.php";
            $this->trans_name = $lang;
        }

        return $this->trans_name[$this->lang][$name];
    }

    // Создание формы с возможностью указать собственный шаблон
    public function createUploadPage(string $path = "resources/page.php")
    {
        $this->createPage($path);
    }

    // Создание страницы
    protected function createPage(string $path)
    {
        include "$path";
    }

    // Подсчет размера
    public function getFileSizeName()
    {
        $size = $this->max_file_size;
        if ($size < 1024) {
            return $size . ' bytes';
        } else if ($size > 1024 && $size < 1048576) {
            return round($size / 1024, 1) . ' KB';
        } else if ($size > 1048576) {
            return round($size / 1048576, 1) . ' MB';
        }
    }

    // Получение данных на сервере

    public function getFileData()
    {
        return $this->files_data;
    }

    public function getFileName()
    {
        return $this->files_data["name"];
    }

    public function getFileType()
    {
        return $this->files_data["type"];
    }

    public function getFileTmpName()
    {
        return $this->files_data["tmp_name"];
    }

    public function getFileError()
    {
        return $this->files_data["error"];
    }

    public function getFileSize()
    {
        return $this->files_data["size"];
    }

    public function getAllFileData()
    {
        if (empty($this->files_all_data)) {
            $data = $this->getFileName();
            $all_data = [];
            for ($i = 0; $i < count($data); $i++) {
                $all_data[] = [
                    "name" => $this->getFileName()[$i],
                    "type" => $this->getFileType()[$i],
                    "tmp_name" => $this->getFileTmpName()[$i],
                    "error" => $this->getFileError()[$i],
                    "size" => $this->getFileSize()[$i]
                ];
            }
            $this->files_all_data = $all_data;
        }

        return $this->files_all_data;
    }

    public function checkFilesAndCreateReport()
    {
        $this->report = [];
        foreach ($this->files_all_data as $key => $data) {
            if ($data["error"]) {
                $this->createReport("errors");
            }
            if (!in_array($data["type"], $this->file_types)) {
                $this->createReport("file_types");
            }
            if (!file_exists($data["tmp_name"])) {
                $this->createReport("tmp_name");
            }
            if ($data["size"] > $this->max_file_size) {
                $this->createReport("size");
            }
            if (!$this->multiple && $key > 0) {
                $this->createReport("multiple");
            }
            if ($this->multiple && $key > ($this->max_files - 1)) {
                $this->createReport("max_files");
            }
        }
        return empty($this->report);
    }

    protected function createReport($name)
    {
        isset($this->report[$name]) ? $this->report[$name]++ : $this->report[$name] = 1;
    }

    public function getReport()
    {
        return $this->report;
    }

    // Постобработка
    public function copyFilesTo(string $path)
    {
        $temp = $this->getFileTmpName();
        $fname = $this->getFileName();
        $result = [];
        foreach ($temp as $key => $tmp) {
            $new_name = md5($tmp . rand()) . md5($fname[$key]){0};
            $result[] = copy($tmp, $path . $new_name) ? $new_name : false;
        }
        return $result;
    }

    public function getCssStyles()
    {
        return file_get_contents("resources/upload.css");
    }

    public function getJsScript()
    {
        return file_get_contents("resources/upload.js");
    }

}

