<?php

namespace App\Controllers\Space;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\SpaceModel;
use Agouti\{UploadImage, Base, Validation};

class EditSpaceController extends MainController
{
    // Изменение пространства
    public function index()
    {
        $uid            = Base::getUid();
        $space_slug     = Request::getPost('space_slug');
        $space_id       = Request::getPostInt('space_id');
        $space_permit   = Request::getPostInt('permit');
        $space_feed     = Request::getPostInt('feed');
        $space_tl       = Request::getPostInt('space_tl');

        $space = SpaceModel::getSpace($space_id, 'id');

        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        }

        $space_name         = Request::getPost('space_name');
        $space_description  = Request::getPost('space_description');
        $space_text         = Request::getPost('space_text');
        $space_short_text   = Request::getPost('space_short_text');

        $redirect   = '/space/edit/' . $space['space_id'];

        Validation::charset_slug($space_slug, 'URL', $redirect);
        Validation::Limits($space_name, lang('titles'), '4', '18', $redirect);
        Validation::Limits($space_description, 'Meta-', '60', '190', $redirect);
        Validation::Limits($space_slug, 'URL', '3', '12', $redirect);
        Validation::Limits($space_short_text, 'TEXT', '10', '250', $redirect);

        $space_color = Request::getPost('color');
        $space_color = empty($space_color) ? $space['space_color'] : $space_color;

        $space_text  = empty($space_text) ? '' : $space_text;

        $slug = SpaceModel::getSpace($space_slug, 'slug');

        if ($slug['space_slug'] != $space['space_slug']) {
            if ($slug) {
                addMsg(lang('url-already-exists'), 'error');
                redirect('/s/' . $space['space_slug']);
            }
        }

        $space_permit   = $space_permit == 1 ? 1 : 0;
        $space_feed     = $space_feed == 1 ? 1 : 0;
        $space_tl       = $space_tl == 1 ? 1 : 0;

        $data = [
            'space_id'              => $space_id,
            'space_slug'            => $space_slug,
            'space_name'            => $space_name,
            'space_description'     => $space_description,
            'space_color'           => $space_color,
            'space_text'            => $space_text,
            'space_short_text'      => $space_short_text,
            'space_permit_users'    => $space_permit,
            'space_feed'            => $space_feed,
            'space_tl'              => $space_tl,
        ];

        SpaceModel::edit($data);

        addMsg(lang('change saved'), 'success');
        redirect('/s/' . $space_slug);
    }

    // Форма изменения пространства
    public function edit()
    {
        $uid        = Base::getUid();
        $space_id   = Request::getInt('id');

        $space = SpaceModel::getSpace($space_id, 'id');

        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        }

        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        $meta = [
            'sheet'         => 'edit',
            'meta_title'    => lang('edit') . ' — ' . $space['space_slug'],
        ];

        $data = [
            'space' => $space,
            'sheet' => 'edit',
        ];

        return view('/space/edit', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Форма изменения логотипа и обложки
    public function logo()
    {
        $uid    = Base::getUid();
        $slug   = Request::get('slug');

        $space = SpaceModel::getSpace($slug, 'slug');

        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        }

        Request::getHead()->addStyles('/assets/css/image-uploader.css');
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');

        $meta = [
            'sheet'         => 'edit-logo',
            'meta_title'    => lang('edit') . ' / ' . lang('logo'),
        ];

        $data = [
            'space' => $space,
            'sheet' => 'logo',
        ];

        return view('/space/edit-logo', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Изменение логотипа и обложки
    public function logoEdit()
    {
        $uid            = Base::getUid();
        $space_slug     = Request::getPost('space_slug');
        $space_id       = Request::getPost('space_id');

        $space = SpaceModel::getSpace($space_id, 'id');

        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        }

        $redirect   = '/space/logo/' . $space['space_slug'] . '/edit';

        // Запишем img
        $img        = $_FILES['images'];
        $check_img  = $_FILES['images']['name'][0];
        if ($check_img) {
            UploadImage::img($img, $space['space_id'], 'space');
        }

        // Запишем баннер
        $cover          = $_FILES['cover'];
        $check_cover    = $_FILES['cover']['name'][0];
        if ($check_cover) {
            UploadImage::cover($cover, $space['space_id'], 'space');
        }

        addMsg(lang('change saved'), 'success');
        redirect($redirect);
    }

    // Удаляем обложку
    public function coverRemove()
    {
        $uid    = Base::getUid();
        $slug   = Request::get('slug');

        $space = SpaceModel::getSpace($slug, 'slug');

        // Проверка доступа 
        if (!accessСheck($space, 'space', $uid, 0, 0)) {
            redirect('/');
        }

        $redirect   = '/space/logo/' . $space['space_slug'] . '/edit';

        // 1920px и 300px
        $path_cover_img       = HLEB_PUBLIC_DIR . '/uploads/spaces/cover/';
        $path_cover_img_small = HLEB_PUBLIC_DIR . '/uploads/spaces/cover/small/';

        // Удалим, кроме дефолтной
        if ($space['space_cover_art'] != 'space_cover_no.jpeg') {
            unlink($path_cover_img . $space['space_cover_art']);
            unlink($path_cover_img_small . $space['space_cover_art']);
        }

        SpaceModel::CoverRemove($space['space_id']);

        addMsg(lang('cover removed'), 'success');
        redirect($redirect);
    }
}
