<?php
$tl = UserData::getUserTl();
$user_id = UserData::getUserId();
function internalRender($nodes, $tl, $user_id)
{
    echo '<ul class="list-style-none mb20 mt10">';
    foreach ($nodes as $node) {
        $minus = $node['reply_parent_id'] == 0 ? ' -ml40' : '';
        $delete = $node['reply_is_deleted'] == 1 ? ' bg-red-200' : '';

        echo '<li class="hidden  mt20' . $minus . $delete . '">
                    <div id="reply_' . $node['reply_id'] . '" class="text-sm">';

        echo '<div class="flex gap">'

            . Html::image($node['avatar'], $node['login'], 'img-sm', 'avatar', 'small') .

            '<span class="gray-600">' . $node['login'] . '</span>
                    
                    <span class=" gray-600 lowercase">' . Html::langDate($node['date']) . '</span>';

        if ($node['reply_parent_id'] != $node['reply_item_id'] && $node['reply_parent_id'] != 0) {
            echo '<a rel="nofollow" class="gray-600" href="#reply_' . $node['reply_parent_id'] . '"><i class="bi-arrow-up"></i></a>';
        }

        if (UserData::checkAdmin()) {
            echo '<span data-id="' . $node['reply_id'] . '" data-type="reply" class="type-action gray-600">
                  <i title="' . __('web.remove') . '" class="bi-trash"></i></span>';
        }

        echo '</div>';

        echo '<div class="max-w780 text-base ind-first-p">' . Content::text($node['content'], 'text') . '</div>
                    <div class="flex gap">' . Html::votes($node, 'reply', 'ps', 'mr5');

        if ($tl >= config('trust-levels.tl_add_reply')) {
            echo '<a data-item_id="' . $node['reply_item_id'] . '" data-type="addform" data-id="' . $node['reply_id'] . '" class="actreply gray-600">' . __('web.reply') . '</a>';
        }

        if ($user_id == $node['reply_user_id']) {
            echo '<a data-item_id="' . $node['reply_item_id'] . '" data-type="editform" data-id="' . $node['reply_id'] . '" class="actreply gray-600">' . __('web.edit') . '</a>';
        }

        echo '</div></div>
                 <div id="reply_addentry' . $node['reply_id'] . '" class="none"></div>';

        if (isset($node['children'])) {
            internalRender($node['children'], $tl, $user_id);
        }

        echo '</li>';
    }
    echo '</ul>';
}

echo internalRender($data['tree'], $tl, $user_id);
