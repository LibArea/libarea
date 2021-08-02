<?php

namespace Lori;

use App\Models\UserModel;
use App\Models\TopicModel;
use App\Models\SpaceModel;
use SimpleImage;
use Lori\Base;

class UploadImage
{
    // Запишем img
    public static function img($img, $content_id, $type)
    {
        if ($type == 'topic') {
            // 160px и 24px
            $path_img       = HLEB_PUBLIC_DIR . '/uploads/topics/';
            $path_img_small = HLEB_PUBLIC_DIR . '/uploads/topics/small/';
            $pref = 't-';
            $default_img = 'topic-default.png';
        } elseif ($type == 'user') {
            $path_img       = HLEB_PUBLIC_DIR . '/uploads/users/avatars/';
            $path_img_small = HLEB_PUBLIC_DIR . '/uploads/users/avatars/small/';
            $pref =  'a-';
            $default_img = 'noavatar.png';
        } elseif ($type == 'space') {
            $path_img       = HLEB_PUBLIC_DIR . '/uploads/spaces/logos/';
            $path_img_small = HLEB_PUBLIC_DIR . '/uploads/spaces/logos/small/';
            $pref =  's-';
            $default_img = 'space_no.png';
        }

        $name = $img['name'][0];

        if ($name) {
            $filename =  $pref . $content_id . '-' . time();
            $file = $img['tmp_name'][0];

            $image = new  SimpleImage();

            $image
                ->fromFile($file)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(160, 160)
                ->toFile($path_img . $filename . '.jpeg', 'image/jpeg')
                ->resize(24, 24)
                ->toFile($path_img_small . $filename . '.jpeg', 'image/jpeg');

            $new_img    = $filename . '.jpeg';

            if ($type == 'topic') {
                $images     = TopicModel::getTopic($content_id, 'id');
                $foto       = $images['topic_img'];
            } elseif ($type == 'user') {
                $images     = UserModel::getUser($content_id, 'id');
                $foto       = $images['avatar'];
            } elseif ($type == 'space') {
                $images     = SpaceModel::getSpace($content_id, 'id');
                $foto       = $images['space_img'];
            }

            // Удалим старую аватарку, кроме дефолтной
            if ($foto != $default_img && $foto != $new_img) {
                chmod($path_img . $foto, 0777);
                chmod($path_img_small . $foto, 0777);
                unlink($path_img . $foto);
                unlink($path_img_small . $foto);
            }

            if ($type == 'topic') {
                // Запишем новую 
                TopicModel::setImg($content_id, $new_img);
            } elseif ($type == 'user') {
                UserModel::setImg($content_id, $new_img);
            } elseif ($type == 'space') {
                SpaceModel::setImg($content_id, $new_img);
            }

            return true;
        }

        return false;
    }

    public static function post_img($img)
    {
        $path_img   = HLEB_PUBLIC_DIR . '/uploads/posts/content/';
        $year       = date('Y') . '/';
        $month      = date('n') . '/';
        $file       = $img['tmp_name'];
        $filename   = 'post-' . time();

        if (!is_dir($path_img . $year . $month)) {
            @mkdir($path_img . $year . $month);
        }

        $image = new  SimpleImage();

        // Обрежем больше 850
        $width_h  = getimagesize($file);
        if ($width_h[0] > 850) {
            $image
                ->fromFile($file)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(850, null)
                ->toFile($path_img . $year . $month . $filename . '.jpeg', 'image/jpeg');

            return '/uploads/posts/content/' . $year . $month . $filename . '.jpeg';
        }

        $image
            ->fromFile($file)
            ->autoOrient()
            ->toFile($path_img . $year . $month . $filename . '.jpeg', 'image/jpeg');

        return '/uploads/posts/content/' . $year . $month . $filename . '.jpeg';
    }


    // Обложка участника и пространств
    public static function cover($cover, $content_id, $type)
    {
        if ($type == 'user') {
            // 1920px / 350px
            $path_cover_img     = HLEB_PUBLIC_DIR . '/uploads/users/cover/';
            $path_cover_small   = HLEB_PUBLIC_DIR . '/uploads/users/cover/small/';
            $pref = 'cover-';
            $default_img = 'cover_art.jpeg';
        } elseif ($type == 'space') {
            $path_cover_img     = HLEB_PUBLIC_DIR . '/uploads/spaces/cover/';
            $path_cover_small   = HLEB_PUBLIC_DIR . '/uploads/spaces/cover/small/';
            $pref = 'cover-';
            $default_img = 'space_cover_no.jpeg';
        }

        if ($cover) {

            $filename =  $pref . $content_id . '-' . time();
            $file_cover = $cover['tmp_name'][0];

            $image = new  SimpleImage();

            $image
                ->fromFile($file_cover)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(1920, 350)
                ->toFile($path_cover_img . $filename . '.jpeg', 'image/jpeg')
                ->resize(390, 124)
                ->toFile($path_cover_small . $filename . '.jpeg', 'image/jpeg');

            $new_cover  = $filename . '.jpeg';
            if ($type == 'user') {
                $user      = UserModel::getUser($content_id, 'id');
                $cover_art  = $user['cover_art'];
            } elseif ($type == 'space') {
                $space      = SpaceModel::getSpace($content_id, 'id');
                $cover_art  = $space['space_cover_art'];
            }

            // Удалим старую аватарку, кроме дефолтной
            if ($cover_art != $default_img && $cover_art != $new_cover) {
                chmod($path_cover_img . $cover_art, 0777);
                unlink($path_cover_img . $cover_art);
                chmod($path_cover_small . $foto, 0777);
                unlink($path_cover_small . $cover_art);
            }

            if ($type == 'user') {
                // Запишем обложку 
                UserModel::setCover($content_id, $new_cover);
            } elseif ($type == 'space') {
                SpaceModel::setCover($content_id, $new_cover);
            }

            return true;
        }

        return false;
    }

    // Обложка поста
    public static function cover_post($cover, $post, $redirect)
    {
        // Проверка ширину
        $width_h  = getimagesize($cover['tmp_name'][0]);
        if ($width_h['0'] < 500) {
            $valid = false;
            Base::addMsg('Ширина меньше 500 пикселей', 'error');
            redirect($redirect);
        }

        $image = new  SimpleImage();
        $path = HLEB_PUBLIC_DIR . '/uploads/posts/cover/';
        $year = date('Y') . '/';
        $file = $cover['tmp_name'][0];
        $filename = 'c-' . time();

        if (!is_dir($path . $year)) {
            @mkdir($path . $year);
        }

        // https://github.com/claviska/SimpleImage
        $image
            ->fromFile($file)  // load image.jpg
            ->autoOrient()     // adjust orientation based on exif data
            ->resize(820, null)
            ->toFile($path . $year . $filename . '.webp', 'image/webp');

        $post_img = $year . $filename . '.webp';

        // Удалим если есть старая
        if ($post['post_content_img'] != $post_img) {
            chmod($path . $post['post_content_img'], 0777);
            unlink($path . $post['post_content_img']);
        }

        return $post_img;
    }

    // Thumb for post
    public static function thumb_post($image)
    {
        $ext = pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (in_array($ext, array('jpg', 'jpeg', 'png'))) {

            $path = HLEB_PUBLIC_DIR . '/uploads/posts/thumbnails/';
            $year = date('Y') . '/';
            $filename = 'p-' . time() . '.' . $ext;
            $file = 'p-' . time();

            if (!is_dir($path . $year)) {
                @mkdir($path . $year);
            }
            $local = $path . $year . $filename;

            if (!file_exists($local)) {
                copy($image, $local);
            }

            $image = new SimpleImage();
            $image
                ->fromFile($local)  // load image.jpg
                ->autoOrient()      // adjust orientation based on exif data
                ->resize(165, null)
                ->toFile($path . $year . $file . '.webp', 'image/webp');

            if (file_exists($local)) {
                unlink($local);
            }

            return $year . $file . '.webp';
        }

        return false;
    }
}
