<?php

declare(strict_types=1);

namespace App\Content\Сheck;

use App\Models\PostModel;

class PostPresence
{
    public static function index(int|string $element, string $type_element = 'id'): array
    {
        $post = PostModel::getPost($element, $type_element);
		
        notEmptyOrView404($post);

        return $post;
    }
}
