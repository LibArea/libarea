<?php

$type = $type ?? false;

foreach ($menu as $key => $item) :
    $tl = $item['tl'] ?? 0;
    if (!empty($item['hr'])) {
        if ($container->user()->id() > 0) {
            echo '<li class="m15"></li>';
        }
    } else {
        if ($container->user()->tl() >= $tl) {
            $css = empty($item['css']) ? false : $item['css'];
            $isActive = $item['id'] == $type ? 'active' : false;
            $class = ($css || $isActive) ? ' class="' . $isActive . ' ' .  $css . '"' : '';
            echo '<li'.$class.'><a href="'.$item['url'].'">';
            if (!empty($item['icon'])) {
                echo '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#'.$item['icon'].'"></use></svg>';
            }
            echo __($item['title']).'</a></li>';
        }
    }
endforeach;

if ($container->user()->id() > 0) {
    if ($topics_user) {
        echo '<div class="flex justify-between items-center">';
        echo '<h4 class="mb5 ml5">'.__('app.preferences').'</h3>';
        echo '<a class="text-sm" title="'.__('app.edit').'" href="'.url('setting.preferences').'">';
        echo '<sup><svg class="icons gray-600"><use xlink:href="/assets/svg/icons.svg#edit"></use></svg></sup>';
        echo '</a></div>';

        $i = 0;
        foreach ($topics_user as $topic) {
            if ($topic['type'] == 2) {
                continue;
            }

            $i++;

            $url = url('topic', ['slug' => $topic['facet_slug']]);
            $blog = '';

            if ($topic['facet_type'] == 'blog') {
                $blog = '<sup class="red">b</span>';
                $url = url('blog', ['slug' => $topic['facet_slug']]);
            }

            echo '<li class="mt15 flex gap items-center justify-between">';
            echo '<a class="flex gap-min items-center" href="'.$url.'">';
            echo Img::image($topic['facet_img'], $topic['facet_title'], 'img-sm', 'logo', 'max');
            echo $topic['facet_title'].' '.$blog;
            echo '</a></li>';
        }

		if ($i < 1) {
			echo '<div class="mt15 ml10">';
			echo '<a class="red text-sm" href="'.url('setting.preferences').'">';
			echo '<span class="red">+</span> '.__('app.add').'</a>';
			echo '<span class="text-sm lowercase ml20 gray-600">'.__('app.preferences').'...</span>';
			echo '</div>';
		}
    }  
}