<?php if (!empty($data['comments'])) : ?>
  <div class="box">
    <h2 class="lowercase text-2xl">
      <?= Html::numWord($post['post_comments_count'], __('app.num_comment'), true); ?>
    </h2>
 
    <?php
    function internalRender($container, $nodes, $post, $level = 0, $type = 'comment')
    {
      foreach ($nodes as  $node) :
        $level =  $level > 1 ? 1 : $level;
        $indent =  $level == 0 ? '0_0' : $level;
    ?>

        <?php if ($node['comment_is_deleted'] == 1 && !$container->user()->admin()) continue; ?>
        <?php if ($node['comment_published'] == 0 && $node['comment_user_id'] != $container->user()->id() && !$container->user()->admin()) continue; ?>

        <div class="block-comment<?php if ($node['comment_is_deleted'] == 1) : ?> m5 bg-red-200<?php endif; ?>">

          <?php if ($node['comment_lo']) : ?>
            <div title="<?= __('app.best_answer'); ?>" class="red right text-2xl p5">✓</div>
          <?php endif; ?>

            <ol class="list-none">
              <li class="comment">
                <a class="anchor-top" id="comment_<?= $node['comment_id']; ?>"></a>
                <div class="comment comment-body comment_level-left-<?= $indent; ?>" id="comment_<?= $node['comment_id']; ?>">
                  <div class="comment-text ml5<?php if ($level == 1) : ?> text-sm<?php endif; ?>">
                    <?= markdown($node['comment_content'], 'text'); ?>
                  </div>
                  <div class="comment-footer justify-between">
                    <div class="flex gap">
                      <?php if ($type != 'qa') : ?>
                        <?= Html::votes($node, 'comment'); ?>
                      <?php endif; ?>

                      <?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_comm_qa'))) : ?>
                        <?php if ($post['post_closed'] == 0 ?? $post['post_is_deleted'] == 0 || $container->user()->admin()) : ?>
                          <a data-id="<?= $node['comment_id']; ?>" data-type="addcomment" class="activ-form gray-600"><?= __('app.reply'); ?></a>
                        <?php endif; ?>
                      <?php endif; ?>
					  
					  <?= insert('/content/comments/menu', ['post' => $post, 'comment' => $node, 'type' => 'qa', 'level' => $indent]); ?>
                    </div>
                    <div class="gray-600 flex gap lowercase mb5">
                      <a class="gray-600" href="<?= url('profile', ['login' => $node['login']]); ?>">
                        <span class="nickname<?php if (Html::loginColor($node['created_at'])) : ?> new<?php endif; ?>"><?= $node['login']; ?></span>
                      </a>
                      <span class="mb-none"><?= langDate($node['comment_date']); ?></span>
                      <?php if ($node['comment_published'] == 0 && $container->user()->admin()) : ?>
                        <span class="ml15 red lowercase"><?= __('app.audits'); ?></span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <?php if ($indent == '0_0') : ?><div class="mb5 br-bottom"></div><?php endif; ?>
                  <?php if ($level == 1) : ?><div class="br-dotted"></div><?php endif; ?>

                  <div id="el_addentry<?= $node['comment_id']; ?>" class="none"></div>
                </div>
              </li>
            </ol>
        </div>

        <?php if (isset($node['children'])) {
          internalRender($container, $node['children'], $post, $level + 1, 'qa');
        } ?>

    <?php endforeach;
    }
    echo internalRender($container, $data['comments'], $data['post']);
    ?>

  </div>
<?php else : ?>
  <?php if ($post['post_closed'] != 1) : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'info']); ?>
  <?php endif; ?>
<?php endif; ?>

<?php if ($data['is_answer']) : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.you_answered'), 'icon' => 'info']); ?>
<?php else : ?>
  <?php if ($container->user()->active()) : ?>
    <?php if ($post['post_feature'] == 1 && $post['post_draft'] == 0 && $post['post_closed'] == 0) : ?>

      <form class="mb15 mt20" action="<?= url('add.comment', method: 'post'); ?>" accept-charset="UTF-8" method="post">
        <?= $container->csrf()->field(); ?>
        <?= insert('/_block/form/editor/notoolbar-img', [
          'height'  => '170px',
          'id'      => $post['post_id'],
          'type'    => 'comment',
        ]); ?>

        <div class="clear mt5">
          <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
          <input type="hidden" name="comment_id" value="0">
          <?= Html::sumbit(__('app.reply')); ?>
        </div>
      </form>

    <?php endif; ?>
  <?php endif; ?>
<?php endif;  ?>

<?php if ($post['post_closed'] == 1) :
  echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.question_closed'), 'icon' => 'lock']);
endif; ?>