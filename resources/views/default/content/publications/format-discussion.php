<?php if (!empty($data['comments'])) : ?>
  <div class="box">
    <div class="flex justify-between mb20">
      <h2 class="lowercase mb15 text-2xl"><?= Html::numWord($post['post_comments_count'], __('app.num_answer'), true); ?></h2>

      <ul class="nav scroll-menu">
        <?php if ($data['sorting'] == 'top') : ?>
          <li class="active"><?= __('app.top'); ?></li>
        <?php else : ?>
          <li><a href="?sort=top#comment"><?= __('app.top'); ?></a></li>
        <?php endif; ?>

        <?php if ($data['sorting'] == 'old') : ?>
          <li class="active mb-none"><?= __('app.new_ones'); ?></li>
        <?php else : ?>
          <li class="mb-none"><a href="?sort=old#comment"><?= __('app.new_ones'); ?></a></li>
        <?php endif; ?>

        <?php if ($data['sorting'] == '') : ?>
          <li class="active"><?= __('app.by_date'); ?></li>
        <?php else : ?>
          <li><a href="<?= $post['post_slug']; ?>#comment"><?= __('app.by_date'); ?></a></li>
        <?php endif; ?>
      </ul>
    </div>

    <?php
    function internalRender($container, $nodes, $post, $level = 0)
    {
      foreach ($nodes as  $node) :
        $level =  $level > 5 ? 5 : $level;
    ?>

        <?php if ($node['comment_is_deleted'] == 1 && !$container->user()->admin()) : ?>
          <div class="gray-600 text-sm m10"><?= __('app.content_deleted', ['name' => __('app.comment')]); ?>...</div>
        <?php else : ?>

          <?php if ($node['comment_published'] == 0 && $node['comment_user_id'] != $container->user()->id() && !$container->user()->admin()) continue; ?>

          <ol class="comments">
            <li>
              <a class="anchor-top" id="comment_<?= $node['comment_id']; ?>"></a>
              <input id="comment_folder_<?= $node['comment_id']; ?>" class="comment-folder-button" type="checkbox">

              <div class="comment comment_level-left-<?= $level; ?><?php if ($node['comment_is_deleted'] == 1) : ?> bg-red-200<?php endif; ?>">
                <label for="comment_folder_<?= $node['comment_id']; ?>" class="comment-folder comment_thread"></label>

                <div class="comment-body">
                    <div class="user-info">
                      <a href="<?= url('profile', ['login' => $node['login']]); ?>">
                        <?= Img::avatar($node['avatar'], $node['login'], 'img-sm mr5', 'small'); ?>
                        <span class="nickname<?php if (Html::loginColor($node['created_at'])) : ?> new<?php endif; ?>">
                          <?= $node['login']; ?>
                        </span>
                      </a>
                      <?php if ($node['comment_is_mobile']) : ?>
                        <svg class="icon small green">
                          <use xlink:href="/assets/svg/icons.svg#mobile"></use>
                        </svg>
                      <?php endif; ?>
                      <?php if ($post['post_user_id'] == $node['comment_user_id']) : ?>
                        <span class="small lowercase green">
                          <?= __('app.author'); ?>
                        </span>
                      <?php endif; ?>
                      <span class="lowercase">
                        <?= langDate($node['comment_date']); ?>
                      </span>
                      <?php if (strtotime($node['comment_modified']) > strtotime($node['comment_date'])) : ?>
                        <span class="mb-none">
                          (<?= __('app.ed'); ?>.)
                        </span>
                      <?php endif; ?>
                      <?php if ($node['comment_published'] == 0 && $container->user()->admin()) : ?>
                        <span class="ml15 red lowercase"><?= __('app.audits'); ?></span>
                      <?php endif; ?>
                      <?php if ($node['comment_lo']) : ?>
                        <svg class="icon red">
                          <use xlink:href="/assets/svg/icons.svg#arrow-up"></use>
                        </svg>
                      <?php endif; ?>

                      <?php if ($node['comment_parent_id'] > 0) : ?>
                        <a class="reply-to" rel="nofollow" href="<?= post_slug($post['post_id'], $post['post_slug']); ?>#comment_<?= $node['comment_parent_id']; ?>">
                          <svg class="icon small">
                            <use xlink:href="/assets/svg/icons.svg#arrow-up"></use>
                          </svg></a>
                      <?php endif; ?>
					  
					  <?= insert('/content/comments/menu', ['post' => $post, 'comment' => $node, 'type' => 'discussion']); ?>
                    </div>
                  <div class="comment-text">
                    <?= markdown($node['comment_content'], 'text'); ?>
                  </div>
                </div>
                <div class="comment-footer">
                  <?= Html::votes($node, 'comment'); ?>

                  <?php if ($post['post_closed'] == 0 && $post['post_is_deleted'] == 0 || $container->user()->admin()) : ?>
                    <a data-id="<?= $node['comment_id']; ?>" data-type="addcomment" class="activ-form gray-600"><?= __('app.reply'); ?></a>
                  <?php endif; ?>
                </div>
                <div id="el_addentry<?= $node['comment_id']; ?>" class="none"></div>
              </div>

              <?php if (isset($node['children'])) {
                internalRender($container, $node['children'], $post, $level + 1);
              } ?>

            </li>
          </ol>
        <?php endif; ?>

    <?php endforeach;
    }

    echo internalRender($container, $data['comments'], $data['post']);
    ?>
  </div>
<?php else : ?>
  <?php if ($post['post_closed'] == 1) : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.close'), 'icon' => 'lock']);  ?>
  <?php elseif (!$container->user()->active()) : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_auth'), 'icon' => 'info']); ?>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'info']); ?>
  <?php endif; ?>
<?php endif; ?>