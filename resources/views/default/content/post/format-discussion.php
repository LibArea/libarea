<?php if (!empty($data['comments'])) : ?>
  <div class="indent-body">
    <div class="flex justify-between mb20">
      <h2 class="lowercase mb15 text-2xl"><?= Html::numWord($post['post_comments_count'], __('app.num_answer'), true); ?></h2>
      <ul class="nav scroll-menu">
        <li<?php if ($data['sorting'] == 'top') : ?> class="active" <?php endif; ?>><a href="?sort=top#comment"><?= __('app.top'); ?></a></li>
          <li<?php if ($data['sorting'] == 'old') : ?> class="active" <?php endif; ?>><a href="?sort=old#comment"><?= __('app.new_ones'); ?></a></li>
            <li<?php if ($data['sorting'] == '') : ?> class="active" <?php endif; ?>><a href="./<?= $post['post_slug']; ?>#comment"><?= __('app.by_date'); ?></a></li>
      </ul>
    </div>

    <?php
    function internalRender($nodes, $post, $level = 0)
    {
      foreach ($nodes as  $node) :
        $level =  $level > 5 ? 5 : $level;
    ?>

        <?php if ($node['comment_is_deleted'] == 1 && !UserData::checkAdmin()) : ?>
          <div class="gray-600 text-sm m10"><?= __('app.content_deleted', ['name' => __('app.comment')]); ?>...</div>
        <?php else : ?>

          <?php if ($node['comment_published'] == 0 && $node['comment_user_id'] != UserData::getUserId() && !UserData::checkAdmin()) continue; ?>

          <ol class="comment-telo"> 
            <li class="relative">
			  <a class="anchor-top" id="comment_<?= $node['comment_id']; ?>"></a>
              <div class="comment-thread comment-level-<?= $level; ?>"></div>

              <div class="content_tree relative comment-level-left-<?= $level; ?><?php if ($node['comment_is_deleted'] == 1) : ?> bg-red-200<?php endif; ?>">
                <div class="content-body">
                  <div class="flex justify-between">
                    <div class="flex text-sm gap-min">
                      <a class="gray-600" href="<?= url('profile', ['login' => $node['login']]); ?>">
                        <?= Img::avatar($node['avatar'], $node['login'], 'img-sm mr5', 'small'); ?>
                        <span class="nickname<?php if (Html::loginColor($node['created_at'])) : ?> green<?php endif; ?>">
                          <?= $node['login']; ?>
                        </span>
                      </a>
                      <?php if ($node['comment_is_mobile']) : ?>
                        <svg class="icons icon-small green">
                          <use xlink:href="/assets/svg/icons.svg#mobile"></use>
                        </svg>
                      <?php endif; ?>
                      <?php if ($post['post_user_id'] == $node['comment_user_id']) : ?>
                        <svg class="icons icon-small sky">
                          <use xlink:href="/assets/svg/icons.svg#mic"></use>
                        </svg>
                      <?php endif; ?>
                      <span class="gray-600 lowercase">
                        <?= Html::langDate($node['comment_date']); ?>
                      </span>
                      <?php if (strtotime($node['comment_modified']) < strtotime($node['comment_date'])) : ?>
                        <span class="gray-600 mb-none">
                          (<?= __('app.ed'); ?>.)
                        </span>
                      <?php endif; ?>
                      <?php if ($node['comment_published'] == 0 && UserData::checkAdmin()) : ?>
                        <span class="ml15 red lowercase"><?= __('app.audits'); ?></span>
                      <?php endif; ?>
                      <?php if ($node['comment_lo']) : ?>
                        <svg class="icons red">
                          <use xlink:href="/assets/svg/icons.svg#arrow-up"></use>
                        </svg>
                      <?php endif; ?>

                      <?php if ($node['comment_parent_id'] > 0) : ?>

                        <a class="gray-600" rel="nofollow" href="<?= post_slug($post['post_id'], $post['post_slug']); ?>#comment_<?= $node['comment_parent_id']; ?>">
                          <svg class="icons icon-small">
                            <use xlink:href="/assets/svg/icons.svg#arrow-up"></use>
                          </svg></a>
                      <?php endif; ?>
                    </div>
                    <?= insert('/content/comments/menu', ['post' => $post, 'comment' => $node, 'type' => 'discussion']); ?>
                  </div>
                  <div class="ind-first-p">
                    <?= markdown($node['comment_content'], 'text'); ?>
                  </div>
                </div>
                <div class="flex text-sm gap mt10 ml5">
                  <?= Html::votes($node, 'comment'); ?>

                  <?php if ($post['post_closed'] == 0 && $post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                    <a data-comment_id="<?= $node['comment_id']; ?>" class="add-comment gray-600"><?= __('app.reply'); ?></a>
                  <?php endif; ?>

                </div>
                <div data-insert="<?= $node['comment_id']; ?>" id="insert_id_<?= $node['comment_id']; ?>" class="none"></div>
              </div>

              <?php if (isset($node['children'])) {
                internalRender($node['children'], $post, $level + 1);
              } ?>

            </li>
          </ol>
        <?php endif; ?>

    <?php endforeach;
    }

    echo internalRender($data['comments'], $data['post']);
    ?>
  </div>
<?php else : ?>
  <?php if ($post['post_closed'] == 1) : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.close'), 'icon' => 'closed']);  ?>
  <?php elseif (!UserData::checkActiveUser()) : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_auth'), 'icon' => 'info']); ?>
  <?php else : ?>	
	<?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'info']); ?>
  <?php endif; ?>
<?php endif; ?>