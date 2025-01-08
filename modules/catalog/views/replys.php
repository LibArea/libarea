<?php
function internalRender($container, $nodes)
{
    foreach ($nodes as $node) {

        $none = $node['reply_parent_id'] == 0 ? ' list-none' : '';
        $delete = $node['reply_is_deleted'] == 1 ? ' bg-red-200' : '';

        echo '<ul class="mt10' . $none . '"><li class="relative mt20' . $delete . '">
                <a id="reply_' . $node['reply_id'] . '" class="anchor-top"></a>
				<div class="comment">';

        echo '<div class="flex gap">' . Img::avatar($node['avatar'], $node['login'], 'img-sm', 'small')

            . '<span class="gray-600">' . $node['login'] . '</span> <span class=" gray-600 lowercase">' . langDate($node['reply_date']) . '</span>';

        if ($node['reply_parent_id'] != $node['reply_item_id'] && $node['reply_parent_id'] != 0) {
            echo '<a rel="nofollow" class="gray-600" href="#reply_' . $node['reply_parent_id'] . '"><svg class="icon"><use xlink:href="/assets/svg/icons.svg#arrow-up"></use></svg></a>';
        }

        if ($container->user()->admin()) {
            echo '<span data-id="' . $node['reply_id'] . '" data-type="reply" class="type-action gray-600">' . __('web.remove') . '</span>';
        }

        echo '</div>';

        echo '<div class="max-w-md text-base ind-first-p">' . markdown($node['content'], 'text') . '</div>
                    <div class="flex gap">' . Html::votes($node, 'reply');

        if ($container->user()->tl() >= config('trust-levels', 'tl_add_reply')) {
            echo '<a data-id="' . $node['reply_id'] . '" data-type="addreply" class="activ-form gray-600">' . __('web.reply') . '</a>';
        }

        if ($container->user()->id() == $node['reply_user_id']) {
            echo '<a data-id="' . $node['reply_id'] . '" data-type="editreply" class="activ-form gray-600">' . __('web.edit') . '</a>';
        }

        echo '</div></div>
                 <div id="el_addentry' . $node['reply_id'] . '" class="none"></div>';

        if (isset($node['children'])) {
            internalRender($container, $node['children']);
        }

        echo '</li></ul>';
    }
}

echo internalRender($container, $data['tree']);
