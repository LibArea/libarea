<?php
/**
 * @author  Foma Tuturov <fomiash@yandex.ru>
 */

namespace Phphleb\Imageresizer;

class  SimpleImage
{
    protected $filename;
    protected $image;
    protected $image_type;
    protected $image_format;

    // Загрузка исходного файла по ссылке
    function load($filename)
    {
        $image_info = getimagesize($filename);
        $this->filename = $filename;
        $this->image_type = $image_info[2];
        $this->image_format = trim(image_type_to_extension($this->image_type), ".");
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
        return empty($this->image) === false;
    }

    // Сохранение файла
    function save($result_filename, $image_type, $compression = 100)
    {
        if ($image_type == IMAGETYPE_JPEG || strtolower($image_type) == "jpeg" || strtolower($image_type) == "jpg") {
            return imagejpeg($this->image, $result_filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF || strtolower($image_type) == "gif") {
            return imagegif($this->image, $result_filename);
        } elseif ($image_type == IMAGETYPE_PNG || strtolower($image_type) == "png") {
            return imagepng($this->image, $result_filename);
        }
        return false;
    }

    // Вывод изображения в браузер
    function output($image_type = IMAGETYPE_JPEG)
    {
        if ($image_type == IMAGETYPE_JPEG || strtolower($image_type) == "jpeg" || strtolower($image_type) == "jpg") {
            return imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF || strtolower($image_type) == "gif") {
            return imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG || strtolower($image_type) == "png") {
            return imagepng($this->image);
        }
        return false;
    }

    // Возврат данных изображения
    function getImage()
    {
        return $this->image;
    }

    // Ширина исходного изображения
    function getWidth()
    {
        return imagesx($this->image);
    }

    // Высота исходного изображения
    function getHeight()
    {
        return imagesy($this->image);
    }

    // Тип исходного файла
    function getImageType()
    {
        return $this->image_type;
    }

    // Расширение (по типу) исходного файла
    function getImageFormat()
    {
        return $this->image_format;
    }

    // Название исходного файла
    function getFilePath()
    {
        return $this->filename;
    }

    // Пропорциональное изменение размера по высоте
    function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    // Пропорциональное изменение размера по ширине
    function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    // Пропорциональное изменение размера в процентном отношении
    function scale($scale)
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    // Изменение размера по указанным ширине и высоте
    function resize($width, $height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        $this->imageCopyResampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    // Пропорциональное изменение размера с ориентацией по центру и без пустых пространств по сторонам (лишнее обрезается)
    function resizeInCenter($width, $height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        $img_width = $this->getWidth();
        $img_height = $this->getHeight();
        if($img_width == 0 || $img_height == 0) return;
        $new_width = $width;
        $new_height = $height;
        $x = $y = 0;
        $dw = $width / $img_width;
        $dh = $height / $img_height;
        if ($dw > $dh) {
            $new_height = $img_height * $dw;
            $y = ($height - $new_height) / 2;
        } else if ($dh > $dw) {
            $new_width = $img_width * $dh;
            $x = ($width - $new_width) / 2;
        }
        $this->imageCopyResampled($new_image, $this->image, $x, $y, 0, 0, $new_width, $new_height, $img_width, $img_height);
        $this->image = $new_image;
    }

    // Обрезание изображения по указанной области
    function cropBySelectedRegion($width, $height, $x, $y)
    {
        $new_image = imagecreatetruecolor($width, $height);
        $img_width = $this->getWidth();
        $img_height = $this->getHeight();
        $this->imageCopyResampled($new_image, $this->image, 0, 0, $x, $y, $img_width, $img_height, $img_width, $img_height);
        $this->image = $new_image;
    }

    // Для установки фона в формате RGB (методу resizeAllInCenter)
    function addRgbColor($red, $green, $blue)
    {
        return [$red, $green, $blue];
    }

    // Пропорциональное изменение размера на прозрачном фоне для PNG (с опциональным закрашиванием для всех типов)
    function resizeAllInCenter($width, $height, $background = null)
    {
        list($r, $g, $b) = $background ? (is_array($background) ? $background : sscanf($background, "#%02x%02x%02x")) : [0, 0, 0];
        $new_image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($new_image, $r, $g, $b);
        if ($background) {
            imagefilledrectangle($new_image, 0, 0, $width, $height, $color);
        } else {
            imagecolortransparent($new_image, $color);
        }
        $img_width = $this->getWidth();
        $img_height = $this->getHeight();
        if($img_width == 0 || $img_height == 0) return;
        $new_width = $width;
        $new_height = $height;
        $x = $y = 0;
        $dw = $width / $img_width;
        $dh = $height / $img_height;
        if ($dw < $dh) {
            $new_height = $img_height * $dw;
            $y = ($height - $new_height) / 2;
        } else if ($dh < $dw) {
            $new_width = $img_width * $dh;
            $x = ($width - $new_width) / 2;
        }
        $this->imageCopyResampled($new_image, $this->image, $x, $y, 0, 0, $new_width, $new_height, $img_width, $img_height);
        $this->image = $new_image;
    }


    protected function imageCopyResampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
    {
        imagecopyresampled(
            $dst_image,
            $src_image,
            intval(ceil($dst_x)),
            intval(ceil($dst_y)),
            intval(ceil($src_x)),
            intval(ceil($src_y)),
            intval(ceil($dst_w)),
            intval(ceil($dst_h)),
            intval($src_w),
            intval($src_h)
        );
    }

}


