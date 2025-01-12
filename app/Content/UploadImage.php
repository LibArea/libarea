<?php

declare(strict_types=1);

use App\Models\{FacetModel, FileModel};
use App\Models\User\{UserModel, SettingModel};
use Phphleb\Imageresizer\SimpleImage;
use App\Bootstrap\Services\User\UserData;

// TODO: We need to rewrite this entire class
class UploadImage
{
    public static function set($file, $content_id, $type)
    {
        if (!empty($file['images']['name'])) {
            self::img($file['images'], $content_id, $type);
        }

        if (!empty($file['cover']['name'])) {
            self::cover($file['cover'], $content_id, $type);
        }
        return false;
    }

    public static function img($img, $content_id, $type)
    {
		if (self::fileTypeCheck($img['type']) === true) {
		   Msg::redirect(__('msg.five_format'), 'error', '/');
		}
		
        switch ($type) {
            case 'facet':
                $path_img       = HLEB_PUBLIC_DIR . Img::PATH['facets_logo'];
                $path_img_small = HLEB_PUBLIC_DIR . Img::PATH['facets_logo_small'];
                $pref = 't-';
                $default_img = 'topic-default.png';
                break;
            default:
                $path_img       = HLEB_PUBLIC_DIR . Img::PATH['avatars'];
                $path_img_small = HLEB_PUBLIC_DIR . Img::PATH['avatars_small'];
                $pref =  'a-';
                $default_img = 'noavatar.png';
        }

        $name = $img['name'];

        if ($name) {

            $filename =  $pref . $content_id . '-' . time();
            $file = $img['tmp_name'];

            $image = new SimpleImage();

            $image->load($file);
            $image->resizeAllInCenter(160, 160, "#ffffff");
            $image->save($path_img . $filename . '.webp', "webp");

            $image->resizeAllInCenter(48, 48, "#ffffff");
            $image->save($path_img_small . $filename . '.webp', "webp");

            $new_img    = $filename . '.webp';

            if ($type == 'facet') {
                $images     = FacetModel::getFacet($content_id, 'id', $type);
                $foto       = $images['topic_img'] ?? false;
            } else {
                $images     = UserModel::get($content_id, 'id');
                $foto       = $images['avatar'] ?? false;
            }

            // Delete the old avatar, except for the default one
            // Удалим старую аватарку, кроме дефолтной
            if ($foto != $default_img && $foto != $new_img) {
                if ($foto != false) {
                    @unlink($path_img . $foto);
                    @unlink($path_img_small . $foto);
                }
            }

            if ($type == 'facet') {
                FacetModel::setImg(['facet_id' => $content_id, 'facet_img' => $new_img]);
            } else {
                SettingModel::setImg(['id' => $content_id, 'avatar' => $new_img, 'updated_at' => date('Y-m-d H:i:s')]);
            }

            return $new_img;
        }

        return false;
    }

    public static function postImg($img, $type, $content_id)
    {
        $path_img   = HLEB_PUBLIC_DIR . Img::PATH['posts_content'];
        $year       = date('Y') . '/';
        $month      = date('n') . '/';
        $file       = $img['tmp_name'];
        $filename   = 'post-' . time();

        // For the body of the post, if png then we will not change the file extension
        // Для тела поста, если png то не будем менять расширение файла
        $file_type = ($img['type'] == 'image/png') ? 'png' : 'webp';

        self::createDir($path_img . $year . $month);

        $image = new SimpleImage();

        $width_h  = getimagesize($file);
        if ($width_h[0] > 1050) {
            $image->load($file);
            $image->resizeToWidth(1050);
            $image->save($path_img . $year . $month . $filename . '.' . $file_type, $file_type, 100);
        } else {
            $image->load($file);
            $image->save($path_img . $year . $month . $filename . '.' . $file_type, $file_type, 100);
        }

        $img_post = Img::PATH['posts_content'] . $year . $month . $filename . '.' . $file_type;
        FileModel::set(
            [
                'file_path'         => $img_post,
                'file_type'         => $type ?? 'none',
                'file_content_id'   => $content_id ?? 0,
                'file_user_id'      => UserData::getUserId(),
                'file_is_deleted'   => 0
            ]
        );

        return $img_post;
    }

    // Member cover
    public static function cover($img, $content_id, $type)
    {
		if (self::fileTypeCheck($img['type']) === true) {
		   Msg::redirect(__('msg.five_format'), 'error', '/');
		}
		
        switch ($type) {
            case 'user':
                // 1920px / 350px
                $path_cover_img     = HLEB_PUBLIC_DIR . Img::PATH['users_cover'];
                $path_cover_small   = HLEB_PUBLIC_DIR . Img::PATH['users_cover_small'];
				$redirect = '/setting/avatars';
                break;
            default:
                $path_cover_img     = HLEB_PUBLIC_DIR . Img::PATH['facets_cover'];
                $path_cover_small   = HLEB_PUBLIC_DIR . Img::PATH['facets_cover_small'];
				$redirect = '/';
        }

        $pref = 'cover-';
        $default_img = 'cover_art.jpeg';

        if ($img) {
			
            $filename =  $pref . $content_id . '-' . time();
            $file_cover = $img['tmp_name'];

            $image = new SimpleImage();
            $image->load($file_cover);
            $image->resize(1720, 350);
            $image->save($path_cover_img . $filename . '.webp', "webp");

            $image->resize(48, 48);
            $image->save($path_cover_small . $filename . '.webp', "webp");

            $new_cover  = $filename . '.webp';

            if ($type == 'user') {
                $user       = UserModel::get($content_id, 'id');
                $cover_art  = $user['cover_art'];
            } else {
                $facet      = FacetModel::getFacet($content_id, 'id', $type);
                $cover_art  = $facet['facet_cover_art'] ?? false;
            }

            // Удалим старую, кроме дефолтной
            if ($cover_art != $default_img && $cover_art != $new_cover) {
                @unlink($path_cover_img . $cover_art);
                if ($cover_art != false) {
                    @unlink($path_cover_small . $cover_art);
                }
            }

            // Запишем обложку 
            $date = date('Y-m-d H:i:s');
            if ($type == 'user') {
                SettingModel::setCover(['id' => $content_id, 'cover_art' => $new_cover, 'updated_at' => $date]);
            } else {
                FacetModel::setCover(['facet_id' => $content_id, 'facet_cover_art' => $new_cover]);
            }

            return true;
        }

        return false;
    }

    public static function coverPost($img, $post, $redirect)
    {
		[$imageInfo, $tempFile] =  Img::createTempImage($img);
	
        $path = HLEB_PUBLIC_DIR . Img::PATH['posts_cover'];
        $year = date('Y') . '/';

        $filename = 'c-' . time();

        self::createDir($path . $year);

        $image = new SimpleImage();
        $image->load($tempFile);
        $image->resizeToWidth(1050);
        $image->save($path . $year . $filename . '.webp', "webp");

		unlink($tempFile);

        $post_img = $year . $filename . '.webp';

        // Delete if there is an old one
        $post_content_img  = $post['post_content_img'] ?? false;
        if ($post_content_img != $post_img) {

            if ($post_content_img != false) {
                @unlink($path . $post_content_img);
            }

            FileModel::removal($post_content_img);
        }

        FileModel::set(
            [
                'file_path'         => $post_img,
                'file_type'         => 'post',
                'file_content_id'   => $post['post_id'] ?? 0,
                'file_user_id'      => UserData::getUserId(),
                'file_is_deleted'   => 0
            ]
        );

        return $post_img;
    }

    // Удаление обложка поста
    public static function coverPostRemove($path_img)
    {
        unlink(HLEB_PUBLIC_DIR . Img::PATH['posts_cover'] . $path_img);

        return FileModel::removal($path_img);
    }

    // Thumb for post
    public static function thumbPost(null|string $image)
    {
		if ($image === null) {
			return false;
		}

        $ext = pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (in_array($ext, array('jpg', 'jpeg', 'png'))) {

            $path = HLEB_PUBLIC_DIR . Img::PATH['posts_thumb'];
            $year = date('Y') . '/';
            $filename = 'p-' . time();

            self::createDir($path . $year);
            $local = $path . $year . $filename;

            if (!file_exists($local)) {
                copy($image, $local);
            }

            if (!file_exists($local)) {
                return false;
            }

            $img = new SimpleImage();
            $img->load($local);
            $img->resizeToWidth(1050);
            $img->save($path . $year . $filename . '.webp', "webp");

            if (file_exists($local)) {
                @unlink($local);
            }

            return $year . $filename . '.webp';
        }

        return false;
    }

    static function createDir($path)
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true) && !is_dir($path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
            }
        }
    }

    public static function fileTypeCheck($type)
    {
        // permitted
        $types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($type, $types)) {
            return true;
        }

        return false;
    }
}
