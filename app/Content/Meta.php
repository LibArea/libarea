<?php

declare(strict_types=1);

class Meta
{
    public static function get(string $title = '', string $description = '', array $m = []): string
    {
        $title = $title ?: config('meta', 'title');
        $description = $description ?: config('meta', 'name');

        $output = '';

        $output .= empty($m['main']) ? "<title>$title | " . config('meta', 'name') . "</title>" : "<title>$title</title>";
        $output .= "<meta name=\"description\" content=\"$description\">";

        if (!empty($m['published_time'])) {
            $output .= "<meta property=\"article:published_time\" content=\"{$m['published_time']}\">";
        }

        $output .= empty($m['type']) ? '<meta property="og:type" content="website">' : "<meta property=\"og:type\" content=\"{$m['type']}\">";

        if (!empty($m)) {
            if (!empty($m['url'])) {
                $output .= "<link rel=\"canonical\" href=\"" . config('meta', 'url') . $m['url'] . "\">";
            }

            if (!empty($m['og'])) {
                $output .= "<meta property=\"og:title\" content=\"$title\">"
                    . "<meta property=\"og:description\" content=\"$description\">"
                    . "<meta property=\"og:url\" content=\"" . config('meta', 'url') . $m['url'] . "\">";

                if (!empty($m['imgurl'])) {
                    $output .= "<meta property=\"og:image\" content=\"" . config('meta', 'url') . $m['imgurl'] . "\">"
                        . "<meta property=\"og:image:width\" content=\"820\">"
                        . "<meta property=\"og:image:height\" content=\"320\">";
                }

                $output .= "<meta name=\"twitter:card\" content=\"summary\">"
                    . "<meta name=\"twitter:title\" content=\"$title\">"
                    . "<meta name=\"twitter:url\" content=\"" . config('meta', 'url') . $m['url'] . "\">"
                    . "<meta property=\"twitter:description\" content=\"$description\">";

                if (!empty($m['imgurl'])) {
                    $output .= "<meta property=\"twitter:image\" content=\"" . config('meta', 'url') . $m['imgurl'] . "\">";
                }
            }

            if (!empty($m['indexing'])) {
                $output .= "<meta name=\"robots\" content=\"noindex\">";
            }
        }

        return $output;
    }

    public static function home(string $sheet)
    {
        $url = match ($sheet) {
            'questions'    => '/questions',
            'posts'        => '/posts',
            'top'        => '/top',
            default        => '/',
        };

        $meta = [
            'main'      => 'main',
            'og'        => true,
            'imgurl'    => config('meta', 'img_path'),
            'url'       => $url,
        ];

        return self::get(config('meta', $sheet . '_title'), config('meta',  $sheet . '_desc'), $meta);
    }

    public static function post(array $content): string
    {
        $indexing = ($content['post_is_deleted'] == 1 || $content['post_published'] == 0) ? true : false;

        $meta = ['type' => 'article', 'indexing'     => $indexing];

        $imgurl = self::postImage($content);
        if (config('meta', 'img_generate') === true) {
            $imgurl = url('og.image', ['id' => $content['post_id']]);
        }

        if ($indexing === false) {
            $meta = [
                'published_time' => $content['post_date'],
                'type'      => 'article',
                'og'        => true,
                'imgurl'    => $imgurl,
                'url'       => post_slug((int)$content['post_id'], $content['post_slug']),
            ];
        }

        $description  = (fragment($content['post_content'], 250) == '') ? strip_tags($content['post_title']) : fragment($content['post_content'], 250);

        return self::get(htmlEncode(strip_tags($content['post_title'])), htmlEncode($description), $meta);
    }


    public static function postImage(array $content): string
    {
        $content_img  = config('meta', 'img_path');

        if ($content['post_content_img']) {
            $content_img  = Img::PATH['posts_cover'] . $content['post_content_img'];
        } elseif ($content['post_thumb_img']) {
            $content_img  = Img::PATH['posts_thumb'] . $content['post_thumb_img'];
        }

        return $content_img;
    }

    public static function profile(string $sheet, array $user): string
    {
        if ($sheet === 'profile') {
            $information = $user['about'];
        }

        $name = $user['login'];
        if ($user['name']) {
            $name = $user['name'] . ' (' . $user['login'] . ') ';
        }

        $title = __('meta.' . $sheet . '_title', ['name' => $name]);
        $description  = __('meta.' . $sheet . '_desc', ['name' => $name, 'information' => $information ?? '...']);

        switch ($sheet) {
            case 'profile_posts':
                $url    = url('profile.posts', ['login' => $user['login']]);
                break;
            case 'profile_comments':
                $url    = url('profile.comments', ['login' => $user['login']]);
                break;
            default:
                $url    = url('profile', ['login' => $user['login']]);
        }

        $meta = self::profileMeta($user, $url);

        return self::get(htmlEncode($title), htmlEncode($description), $meta);
    }

    // Индексация профиля: условия
    // Если количество лайков меньше 3, если профиль удален и если он в бан листе
    public static function profileMeta(array $user, string $url): array
    {
        $meta = ['og' => false];
        if ($user['up_count'] > 3) {
            $meta = [
                'og'        => true,
                'imgurl'    => '/uploads/users/avatars/' . $user['avatar'],
                'url'       => $url,
            ];
        }

        // If the user is on the ban list or deleted, then we prohibit indexing of the profile
        // Если пользователь в бан листе или удален, то запрещаем индексацию профиля
        if ($user['ban_list'] == 1 || $user['is_deleted'] == 1) {
            $meta['indexing'] = true;
        }

        // If the user has not contributed, then the profile is prohibited from indexing (by the number of likes)
        // Если пользователь не внес вклад, то профиль запретить к индексированию (по количеству лайков)
        if ($user['up_count'] < 3) {
            $meta['indexing'] = true;
        }

        return $meta;
    }


    public static function facet(string $sheet, array $facet, string $type = 'topic', array $topic = []): string
    {
        switch ($sheet) {
            case 'questions':
                $url    = url($type . '.questions', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' . __('app.questions');
                $description = __('meta.feed_facet_questions_desc') . $facet['facet_description'];
                break;
            case 'posts':
                $url    = url($type . '.posts', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' . __('app.posts');
                $description = __('meta.feed_facet_posts_desc') . $facet['facet_description'];
                break;
            case 'recommend':
                $url    =  url($type . '.recommend', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' .  __('app.rec_posts');
                $description  = __('meta.feed_facet_rec_posts_desc', ['name' => $facet['facet_seo_title']]) . $facet['facet_description'];
                break;
            case 'info':
                $url    = url($type . '.info', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' . __('app.info');
                $description = __('meta.facet_info_desc', ['name' => $facet['facet_seo_title']]) . $facet['facet_description'];
                break;
            case 'writers':
                $url    = url($type . '.writers', ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' . __('app.writers');
                $description = __('meta.facet_writers_desc', ['name' => $facet['facet_seo_title']]) . $facet['facet_description'];
                break;
            case 'blog.topics':
                $url    = url($type . '.topics', ['slug' => $facet['facet_slug'], 'tslug' => $topic['facet_slug']]);
                $title  = $topic['facet_seo_title'] . ' — ' . $facet['facet_seo_title'];
                $description = __('meta.facet_topics_desc', ['name' => $topic['facet_seo_title']]) . $facet['facet_description'];
                break;
            default: // facet.feed 
                $url    = url($type, ['slug' => $facet['facet_slug']]);
                $title  = $facet['facet_seo_title'] . ' — ' .  __('app.feed');
                $description = __('meta.feed_facet_desc') . $facet['facet_description'];
        }

        $meta = [
            'og'        => true,
            'imgurl'    => Img::PATH['facets_logo'] . $facet['facet_img'],
            'url'       =>  $url,
        ];

        if ($facet['facet_is_deleted'] == 1) {
            $meta['indexing'] = true;
            $meta['og'] = false;
        }

        return self::get($title, $description, $meta);
    }
}
