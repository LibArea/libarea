<?php

use App\Models\{FacetModel, FileModel};
use App\Models\User\{UserModel, SettingModel};

class UploadImage
{
    // Запишем img
    public static function img($img, $content_id, $type)
    {
        switch ($type) {
            case 'topic':
                $path_img       = HLEB_PUBLIC_DIR . AG_PATH_FACETS_LOGOS;
                $path_img_small = HLEB_PUBLIC_DIR . AG_PATH_FACETS_SMALL_LOGOS;
                $pref = 't-';
                $default_img = 'topic-default.png';
                break;
            default:
                $path_img       = HLEB_PUBLIC_DIR . AG_PATH_USERS_AVATARS;
                $path_img_small = HLEB_PUBLIC_DIR . AG_PATH_USERS_SMALL_AVATARS;
                $pref =  'a-';
                $default_img = 'noavatar.png';
        }

        $name = $img['name'];

        if ($name) {
            $filename =  $pref . $content_id . '-' . time();
            $file = $img['tmp_name'];

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
                $images     = FacetModel::getFacet($content_id, 'id');
                $foto       = $images['topic_img'];
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
                FacetModel::setImg($content_id, $new_img);
            } else {
                $date = date('Y-m-d H:i:s');
                SettingModel::setImg($content_id, $new_img, $date);
            }

            return $new_img;
        }

        return false;
    }

    public static function post_img($img, $user_id)
    {
        $path_img   = HLEB_PUBLIC_DIR . AG_PATH_POSTS_CONTENT;
        $year       = date('Y') . '/';
        $month      = date('n') . '/';
        $file       = $img['tmp_name'];
        $filename   = 'post-' . time();
        
 
        self::createDir($path_img . $year . $month);

        $image = new  SimpleImage();

        $width_h  = getimagesize($file);
        if ($width_h[0] > 850) {
            $image
                ->fromFile($file)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(850, null)
                ->toFile($path_img . $year . $month . $filename . '.jpeg', 'image/jpeg');
        } else {
            $image
                ->fromFile($file)
                ->autoOrient()
                ->toFile($path_img . $year . $month . $filename . '.jpeg', 'image/jpeg');
        }
        
         $img_post = AG_PATH_POSTS_CONTENT . $year . $month . $filename . '.jpeg';   
         $params = [
            'file_path'         => $img_post,
            'file_type'         => 'post-telo',
            'file_content_id'   => $post['post_id'] ?? 0,
            'file_user_id'      => $user_id,
            'file_date'         => date('Y-m-d H:i:s'),
            'file_is_deleted'   => 0

        ];
        FileModel::set($params);

        return $img_post;
    }

    // Обложка участника
    public static function cover($cover, $content_id, $type)
    {
        switch ($type) {
            case 'user':
                // 1920px / 350px
                $path_cover_img     = HLEB_PUBLIC_DIR . AG_PATH_USERS_COVER;
                $path_cover_small   = HLEB_PUBLIC_DIR . AG_PATH_USERS_SMALL_COVER;
                break;
            default:
                $path_cover_img     = HLEB_PUBLIC_DIR . AG_PATH_BLOGS_COVER;
                $path_cover_small   = HLEB_PUBLIC_DIR . AG_PATH_BLOGS_SMALL_COVER;
        }

        $pref = 'cover-';
        $default_img = 'cover_art.jpeg';

        if ($cover) {

            $filename =  $pref . $content_id . '-' . time();
            $file_cover = $cover['tmp_name'];

            $image = new  SimpleImage();
            $image
                ->fromFile($file_cover)  // load image.jpg
                ->autoOrient()     // adjust orientation based on exif data
                ->resize(1720, 350)
                ->toFile($path_cover_img . $filename . '.jpeg', 'image/jpeg')
                ->resize(390, 124)
                ->toFile($path_cover_small . $filename . '.jpeg', 'image/jpeg');

            $new_cover  = $filename . '.jpeg';
            
            if ($type == 'user') {
                $user       = UserModel::getUser($content_id, 'id');
                $cover_art  = $user['user_cover_art'];
            } else {
                $facet      = FacetModel::getFacet($content_id, 'id');
                $cover_art  = $facet['facet_cover_art'];
            }

            // Удалим старую, кроме дефолтной
            if ($cover_art != $default_img && $cover_art != $new_cover) {
                @unlink($path_cover_img . $cover_art);
                @unlink($path_cover_small . $cover_art);
            }

            // Запишем обложку 
            $date = date('Y-m-d H:i:s');
            if ($type == 'user') {
                SettingModel::setCover($content_id, $new_cover, $date);
            } else {
                FacetModel::setCover($content_id, $new_cover);
            }

            return true;
        }

        return false;
    }

    // Обложка поста
    public static function cover_post($cover, $post, $redirect, $user_id)
    {
        // Проверка ширину
        $width_h  = getimagesize($cover['tmp_name']);
        if ($width_h < 500) {
            $valid = false;
            addMsg('Ширина меньше 500 пикселей', 'error');
            redirect($redirect);
        }

        $image = new  SimpleImage();
        $path = HLEB_PUBLIC_DIR . AG_PATH_POSTS_COVER;
        $year = date('Y') . '/';
        $file = $cover['tmp_name'];
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
            FileModel::removal($post['post_content_img'], $user_id);
        }

        $params = [
            'file_path'         => $post_img,
            'file_type'         => 'post',
            'file_content_id'   => $post['post_id'] ?? 0,
            'file_user_id'      => $user_id,
            'file_date'         => date('Y-m-d H:i:s'),
            'file_is_deleted'   => 0

        ];
        FileModel::set($params);

        return $post_img;
    }
    
    // Удаление обложка поста
    public static function cover_post_remove($path_img, $user_id)
    {
       unlink(HLEB_PUBLIC_DIR . AG_PATH_POSTS_COVER . $path_img);
       
       return FileModel::removal($path_img, $user_id);
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
