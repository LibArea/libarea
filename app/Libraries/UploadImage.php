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
        if($type == 'topic') {
             // 160px и 24px
             $path_img       = HLEB_PUBLIC_DIR. '/uploads/topics/';
             $path_img_small = HLEB_PUBLIC_DIR. '/uploads/topics/small/';
             $pref = 't-';
             $default_img = 'topic-default.png';
        } elseif ($type == 'user') {
            $path_img       = HLEB_PUBLIC_DIR. '/uploads/users/avatars/';
            $path_img_small = HLEB_PUBLIC_DIR. '/uploads/users/avatars/small/';
            $pref =  'a-';
            $default_img = 'noavatar.png';
        } elseif ($type == 'space') {
            $path_img       = HLEB_PUBLIC_DIR. '/uploads/spaces/logos/';
            $path_img_small = HLEB_PUBLIC_DIR. '/uploads/spaces/logos/small/';
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
                ->toFile($path_img . $filename .'.jpeg', 'image/jpeg')
                ->resize(24, 24)
                ->toFile($path_img_small . $filename .'.jpeg', 'image/jpeg');
                    
            $new_img    = $filename . '.jpeg';

            if ($type == 'topic') {
                $images     = TopicModel::getTopicId($content_id);
                $foto       = $images['topic_img'];
            } elseif ($type == 'user') {
                $images     = UserModel::getUserId($content_id);
                $foto       = $images['avatar'];
 
            } elseif ($type == 'space') {
                $images     = SpaceModel::getSpaceId($content_id);
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
    
    // Работа с контентом
    public static function cover($cover, $content_id, $type)
    {
        if($type == 'user') {
            // 1920px / 350px
            $path_cover_img       = HLEB_PUBLIC_DIR. '/uploads/users/cover/';
            $pref = 'cover-';
            $default_img = 'cover_art.jpeg';
        } elseif ($type == 'space') {
            $path_cover_img       = HLEB_PUBLIC_DIR. '/uploads/spaces/cover/';
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
                ->toFile($path_cover_img . $filename .'.jpeg', 'image/jpeg');
                    
            $new_cover  = $filename . '.jpeg';
            if ($type == 'user') {
                $user      = UserModel::getUserId($content_id);
                $cover_art  = $user['cover_art'];
            } elseif ($type == 'space') {
                $space      = SpaceModel::getSpaceId($content_id);
                $cover_art  = $space['space_cover_art'];
            }
            
            // Удалим старую аватарку, кроме дефолтной
            if ($cover_art != $default_img && $cover_art != $new_cover) {
                chmod($path_cover_img . $cover_art, 0777);
                unlink($path_cover_img . $cover_art);
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
}
