<?php

declare(strict_types=1);

namespace App\Services\Сheck;

use App\Models\PostModel;
use UserData;

class PostPresence
{
    public static function index(int $element, string $type_element = 'id') : array
    {
        $post = PostModel::getPost($element, $type_element, UserData::get());
        
        notEmptyOrView404($post);

        return $post;
    }
}
