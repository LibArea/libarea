<?php

namespace App\Traits;

use Cocur\Slugify\Slugify;

trait Slug
{
    private function getSlug($title)
    {
        $slugify = new Slugify();
        $uri = $slugify->slugify($title);

        return substr($uri, 0, 90);
    }
}
