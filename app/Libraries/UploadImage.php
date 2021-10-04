<?php

namespace Agouti;

use App\Models\{UserModel, TopicModel, SpaceModel};
use SimpleImage;

class UploadImage
{
    // Запишем img
    public static function img($img, $content_id, $type)
    {
        switch ($type) {
            case 'topic':
                $path_img       = HLEB_PUBLIC_DIR . AG_PATH_TOPICS_LOGOS;
                $path_img_small = HLEB_PUBLIC_DIR . AG_PATH_TOPICS_SMALL_LOGOS;
                $pref = 't-';
                $default_img = 'topic-default.png';
                break;
            case 'space':
                $path_img       = HLEB_PUBLIC_DIR . AG_PATH_SPACES_LOGOS;
                $path_img_small = HLEB_PUBLIC_DIR . AG_PATH_SPACES_SMALL_LOGOS;
                $pref =  's-';
                $default_img = 'space_no.png';
                break;
            default:
                $path_img       = HLEB_PUBLIC_DIR . AG_PATH_USERS_AVATARS;
                $path_img_small = HLEB_PUBLIC_DIR . AG_PATH_USERS_SMALL_AVATARS;
                $pref =  'a-';
                $default_img = 'noavatar.png';
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
                ->resize(48, 48)
                ->toFile($path_img_small . $filename . '.jpeg', 'image/jpeg');

            $new_img    = $filename . '.jpeg';

            if ($type == 'topic') {
                $images     = TopicModel::getTopic($content_id, 'id');
                $foto       = $images['topic_img'];
            } elseif ($type == 'space') {
                $images     = SpaceModel::getSpace($content_id, 'id');
                $foto       = $images['space_img'];
            } else {
                $images     = UserModel::getUser($content_id, 'id');
                $foto       = $images['user_avatar'];
            }

            // Удалим старую аватарку, кроме дефолтной
            if ($foto != $default_img && $foto != $new_img) {
                @unlink($path_img . $foto);
                @unlink($path_img_small . $foto);
            }

            if ($type == 'topic') {
                TopicModel::setImg($content_id, $new_img);
            } elseif ($type == 'space') {
                SpaceModel::setImg($content_id, $new_img);
            } else {
                $date = date('Y-m-d H:i:s');
                UserModel::setImg($content_id, $new_img, $date);
            }

            return $new_img;
        }

        return false;
    }

    public static function post_img($img)
    {
        $path_img   = HLEB_PUBLIC_DIR . AG_PATH_POSTS_CONTENT;
        $year       = date('Y') . '/';
        $month      = date('n') . '/';
        $file       = $img['tmp_name'];
        $filename   = 'post-' . time();

        self::createDir($path_img . $year . $month);

        $image = new  SimpleImage();

        // Обрежем больше 850
        $width_h  = getimagesize($file);
        if ($width_h[0] > 850) {
            $image
                ->fromFile($file)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(850, null)
                ->toFile($path_img . $year . $month . $filename . '.jpeg', 'image/jpeg');

            return AG_PATH_POSTS_CONTENT . $year . $month . $filename . '.jpeg';
        }

        $image
            ->fromFile($file)
            ->autoOrient()
            ->toFile($path_img . $year . $month . $filename . '.jpeg', 'image/jpeg');

        return AG_PATH_POSTS_CONTENT . $year . $month . $filename . '.jpeg';
    }

    // Обложка участника и пространств
    public static function cover($cover, $content_id, $type)
    {
        // 1920px / 350px
        $path_cover_img     = HLEB_PUBLIC_DIR . AG_PATH_USERS_COVER;
        $path_cover_small   = HLEB_PUBLIC_DIR . AG_PATH_USERS_SMALL_COVER;
        $pref = 'cover-';
        $default_img = 'cover_art.jpeg';

        if ($type == 'space') {
            $path_cover_img     = HLEB_PUBLIC_DIR . AG_PATH_SPACES_COVER;
            $path_cover_small   = HLEB_PUBLIC_DIR . AG_PATH_SPACES_SMALL_COVER;
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
                $cover_art  = $user['user_cover_art'];
            } elseif ($type == 'space') {
                $space      = SpaceModel::getSpace($content_id, 'id');
                $cover_art  = $space['space_cover_art'];
            }

            // Удалим старую аватарку, кроме дефолтной
            if ($cover_art != $default_img && $cover_art != $new_cover) {
                @unlink($path_cover_img . $cover_art);
                @unlink($path_cover_small . $cover_art);
            }
            
            if ($type == 'user') {
                // Запишем обложку 
                $date = date('Y-m-d H:i:s');
                UserModel::setCover($content_id, $new_cover, $date);
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
            addMsg('Ширина меньше 500 пикселей', 'error');
            redirect($redirect);
        }

        $image = new  SimpleImage();
        $path = HLEB_PUBLIC_DIR . AG_PATH_POSTS_COVER;
        $year = date('Y') . '/';
        $file = $cover['tmp_name'][0];
        $filename = 'c-' . time();

        self::createDir($path . $year);

        // https://github.com/claviska/SimpleImage
        $image
            ->fromFile($file)  // load image.jpg
            ->autoOrient()     // adjust orientation based on exif data
            ->resize(820, null)
            ->toFile($path . $year . $filename . '.webp', 'image/webp');

        $post_img = $year . $filename . '.webp';

        // Удалим если есть старая
        if ($post['post_content_img'] != $post_img) {
            @unlink($path . $post['post_content_img']);
        }

        return $post_img;
    }

    // Thumb for post
    public static function thumb_post($image)
    {
        $ext = pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (in_array($ext, array('jpg', 'jpeg', 'png'))) {

            $path = HLEB_PUBLIC_DIR . AG_PATH_POSTS_THUMB;
            $year = date('Y') . '/';
            $filename = 'p-' . time() . '.' . $ext;
            $file = 'p-' . time();

            self::createDir($path . $year);
            $local = $path . $year . $filename;

            if (!file_exists($local)) {
                copy($image, $local);
            }

            $image = new SimpleImage();
            $image
                ->fromFile($local)  // load image.jpg
                ->autoOrient()      // adjust orientation based on exif data
                ->resize(260, null)
                ->toFile($path . $year . $file . '.webp', 'image/webp');

            if (file_exists($local)) {
                @unlink($local);
            }

            return $year . $file . '.webp';
        }

        return false;
    }
    
    static function createDir($path) 
    {
        if (!is_dir($path)) {
                mkdir($path, 0777, true);
        }
    }
}
