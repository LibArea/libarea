<?php

declare(strict_types=1);

namespace App\Content\Сheck;

use Hleb\Static\Container;
use App\Models\FacetModel;
use App\Models\Auth\AuthModel;
use App\Models\User\InvitationModel;
use Msg;

use Respect\Validation\Validator as v;

class Validator
{
    public static function publication($data, $type, $redirect)
    {
		if ($type != 'post') {
			$title = str_replace("&nbsp;", '', $data['title']);
			if (v::stringType()->length(6, 250)->validate($title) === false) {
				Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.title') . '»']), 'error', $redirect);
			}
		}

        if (v::stringType()->length(6, 25000)->validate($data['content']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.content') . '»']), 'error', $redirect);
        }

        // Let's check the presence of the facet before adding it	
        // Проверим наличие фасета перед добавлением	
        if (!$data['facet_select'] ?? false) {
            Msg::redirect(__('msg.select_topic'), 'error', $redirect);
        }

        return true;
    }

    public static function message($data, $redirect)
    {
        if (v::stringType()->length(6, 10000)->validate($data['content']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.content') . '»']), 'error', $redirect);
        }

        return true;
    }


    public static function comment($data, $redirect)
    {
        if (v::stringType()->length(6, 5000)->validate($data['content']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.content') . '»']), 'error', $redirect);
        }

        return true;
    }

    public static function login($data)
    {
        $redirect = url('login');

        if (v::email()->isValid($data['email']) === false) {
            Msg::redirect(__('msg.email_correctness'), 'error', $redirect);
        }

        $user = AuthModel::getUser($data['email'], 'email');

        if (empty($user['id'])) {
            Msg::redirect(__('msg.no_user'), 'error', $redirect);
        }

        // Is it on the ban list
        // Находится ли в бан- листе
        if (AuthModel::isBan($user['id'])) {
            Msg::redirect(__('msg.account_verified'), 'error', $redirect);
        }

        if (!AuthModel::isActivated($user['id'])) {
            Msg::redirect(__('msg.not_activated'), 'error', $redirect);
        }

        if (AuthModel::isDeleted($user['id'])) {
            Msg::redirect(__('msg.no_user'), 'error', '/');
        }

        if (!password_verify($data['password'], $user['password'])) {
            Msg::redirect(__('msg.not_correct'), 'error', $redirect);
        }

        return $user;
    }

    public static function invite(string $email, int $quantity)
    {
        $redirect = url('invitations');

        if (v::email()->isValid($email) === false) {
            Msg::redirect(__('msg.email_correctness'), 'error', $redirect);
        }

        $user = AuthModel::getUser($email, 'email');
        if (!empty($user['email'])) {
            Msg::redirect(__('msg.user_already'), 'error', $redirect);
        }

        $inv_user = InvitationModel::duplicate($email);
        if (!empty($inv_user['invitation_email'])) {
            if ($inv_user['invitation_email'] == $email) {
                Msg::redirect(__('msg.invate_replay'), 'error', $redirect);
            }
        }

        if ($quantity >= config('trust-levels', 'perDay_invite')) {
            Msg::redirect(__('msg.invate_limit_stop'), 'error', $redirect);
        }

        return true;
    }

    public static function setting($data)
    {
        $redirect = url('setting');

        if (v::stringType()->length(0, 11)->validate($data['name']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.name') . '»']), 'error', $redirect);
        }

        if (v::stringType()->length(0, 255)->validate($data['about']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.about') . '»']), 'error', $redirect);
        }


        if ($data['public_email']) {
            if (v::email()->isValid($data['public_email']) === false) {
                Msg::redirect(__('msg.email_correctness'), 'error', $redirect);
            }
        }

        return true;
    }

    public static function security(array $data, string $email)
    {
        $redirect   = '/setting/security';

        if ($data['password2'] != $data['password3']) {
            Msg::redirect(__('msg.pass_match_err'), 'error', $redirect);
        }

        if (substr_count($data['password2'], ' ') > 0) {
            Msg::redirect(__('msg.password_spaces'), 'error', $redirect);
        }

        if (v::stringType()->length(6, 10000)->validate($data['password2']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.password') . '»']), 'error', $redirect);
        }

        $userInfo   = AuthModel::getUser($email, 'email');
        if (!password_verify($data['password'], $userInfo['password'])) {
            Msg::redirect(__('msg.old_error'), 'error', $redirect);
        }

        return true;
    }

    public static function rulesNewEmail($email): bool
    {
        if (v::email()->isValid($email) === false) {
            return false;
        }

        return true;
    }
	
    public static function addFacet($data, $facet_type)
    {
        $container = Container::getContainer();
        $redirect = ($facet_type === 'category') ? url('web') : url('facet.form.add', ['type' => $facet_type]);

        if ($facet_type === 'blog') {
            if (!$container->user()->admin()) {
                if (in_array($data['facet_slug'], config('stop-blog', 'list'))) {
                    Msg::redirect(__('msg.went_wrong'), 'error', $redirect);
                }
            }
        }

        if (v::stringType()->length(3, 64)->validate($data['facet_title']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.title') . '»']), 'error', $redirect);
        }

        if (v::stringType()->length(9, 160)->validate($data['facet_short_description']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.short_description') . '»']), 'error', $redirect);
        }

        if (v::slug()->length(3, 43)->validate($data['facet_slug']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.slug') . '»']), 'error', $redirect);
        }

        if (FacetModel::uniqueSlug($data['facet_slug'], $facet_type)) {
            Msg::redirect(__('msg.repeat_url'), 'error', $redirect);
        }

        if (preg_match('/\s/', $data['facet_slug']) || strpos($data['facet_slug'], ' ')) {
            Msg::redirect(__('msg.url_gaps'), 'error', $redirect);
        }
    }
	
    public static function editFacet($data, $facet)
    {
        $container = Container::getContainer();

        // ['topic', 'blog', 'category', 'section']
        if (!in_array($data['facet_type'], config('facets', 'permitted'))) {
            Msg::redirect(__('msg.went_wrong'), 'error');
        }

        if ($facet == false) {
            Msg::redirect(__('msg.went_wrong'), 'error');
        }

        $redirect = url('facet.form.edit', ['type' => $facet['facet_type'], 'id' => $facet['facet_id']]);

        // Доступ получает только автор и админ
        if ($facet['facet_user_id'] != $container->user()->id() && !$container->user()->admin()) {
            Msg::redirect(__('msg.went_wrong'), 'error', $redirect);
        }

        // Изменять тип темы может только персонал
        $new_type = $facet['facet_type'];
        if ($data['facet_type'] != $facet['facet_type']) {
            if ($container->user()->admin()) $new_type = $data['facet_type'];
        }

        // Проверка длины
        if (v::stringType()->length(3, 64)->validate($data['facet_title']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.title') . '»']), 'error', $redirect);
        }

        if (v::stringType()->length(3, 225)->validate($data['facet_description']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.meta_description') . '»']), 'error', $redirect);
        }

        if (v::stringType()->length(9, 160)->validate($data['facet_short_description']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.short_description') . '»']), 'error', $redirect);
        }

        if (v::stringType()->length(3, 225)->validate($data['facet_seo_title']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.slug') . '»']), 'error', $redirect);
        }

        // Slug
        if (v::slug()->length(3, 43)->validate($data['facet_slug']) === false) {
            Msg::redirect(__('msg.string_length', ['name' => '«' . __('msg.slug') . '»']), 'error', $redirect);
        }

        // Проверим повтор URL                       
        if ($data['facet_slug'] != $facet['facet_slug']) {
            if (FacetModel::uniqueSlug($data['facet_slug'], $new_type)) {
                Msg::redirect(__('msg.repeat_url'), 'error', $redirect);
            }
        }

        return $new_type;
    }
}
