<?php

declare(strict_types=1);

namespace App\Content\Сheck;

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
}
