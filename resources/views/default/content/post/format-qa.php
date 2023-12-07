<?php if (!empty($data['comments'])) : ?>
  <div class="indent-body">
    <h2 class="lowercase m0 text-2xl">
      <?= Html::numWord($post['post_comments_count'], __('app.num_comment'), true); ?>
    </h2>

    <?php
    function internalRender($nodes, $post, $level = 0, $type = 'comment')
    {
      foreach ($nodes as  $node) :
        $level =  $level > 1 ? 1 : $level;
		$indent =  $level == 0 ? '0_0' : $level;
    ?>

        <?php if ($node['comment_is_deleted'] == 1 && !UserData::checkAdmin()) continue; ?>
        <?php if ($node['comment_published'] == 0 && $node['comment_user_id'] != UserData::getUserId() && !UserData::checkAdmin()) continue; ?>

        <div class="block-comment<?php if ($node['comment_is_deleted'] == 1) : ?> m5 bg-red-200<?php endif; ?>">

          <?php if ($node['comment_lo']) : ?>
            <div title="<?= __('app.best_answer'); ?>" class="red right text-2xl p5">✓</div>
          <?php endif; ?>

          <?php if (UserData::getUserId() == $node['comment_user_id']) { ?> <?php $otvet = 1; ?><?php } ?>
		  
          <ol class="list-none">
            <li class="comment" id="comment_<?= $node['comment_id']; ?>">
              <div class="comment-comm comment_thread"></div>
              <div class="comment_body comment_level-left-<?= $indent; ?>" id="comment_<?= $node['comment_id']; ?>">
			  
			    <?= insert('/content/comments/menu', ['post' => $post, 'comment' => $node, 'type' => 'qa', 'level' => $indent]); ?>
			  
                <div class="max-w780 ind-first-p ml5<?php if($level == 1) : ?> text-sm<?php endif; ?>">
                  <?= markdown($node['comment_content'], 'text'); ?>
                </div>
                <div class="comment_footer justify-between">
                  <div class="flex gap">
                    <?php if ($type != 'qa') : ?>
                      <?= Html::votes($node, 'comment'); ?>
                    <?php endif; ?>

                    <?php if (UserData::getRegType(config('trust-levels.tl_add_comm_qa'))) : ?>
                      <?php if ($post['post_closed'] == 0 ?? $post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                        <a data-comment_id="<?= $node['comment_id']; ?>" class="add-comment gray-600"><?= __('app.reply'); ?></a>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                  <div class="gray-600 flex gap lowercase mb5">
                    <a class="gray-600<?php if (Html::loginColor($node['created_at'] ?? false)) : ?> green<?php endif; ?>" href="<?= url('profile', ['login' => $node['login']]); ?>">
                      <span class="nickname"><?= $node['login']; ?></span>
                    </a>
                    <span class="mb-none"><?= Html::langDate($node['comment_date']); ?>
                      <?php if ($type != 'qa') : ?>
                        <?php if (empty($node['edit'])) : ?>
                          (<?= __('app.ed'); ?>.)
                        <?php endif; ?>
                      <?php endif; ?>
                    </span>
                    <?php if ($node['comment_published'] == 0 && UserData::checkAdmin()) : ?>
                      <span class="ml15 red lowercase"><?= __('app.audits'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
				 <?php if ($indent == '0_0') :?><div class="mb5 br-bottom"></div><?php endif; ?>
				 <?php if ($level == 1) :?><div class="br-dotted"></div><?php endif; ?>
				 
                <div data-insert="<?= $node['comment_id']; ?>" id="insert_id_<?= $node['comment_id']; ?>" class="none"></div>
				</div>
            </li>
          </ol>
        </div>

        <?php if (isset($node['children'])) {
          internalRender($node['children'], $post, $level + 1, 'qa');
        } ?>

    <?php endforeach;
    }
    echo internalRender($data['comments'], $data['post']);
    ?>

  </div>
<?php else : ?>
  <?php if ($post['post_closed'] != 1) : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'info']); ?>
  <?php endif; ?>
<?php endif; ?>

<?php if (!empty($otvet)) : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.you_answered'), 'icon' => 'info']); ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?php if ($post['post_feature'] == 1 && $post['post_draft'] == 0 && $post['post_closed'] == 0) : ?>

      <form class="mb15 mt20" action="<?= url('content.create', ['type' => 'comment']); ?>" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
        <?= insert('/_block/form/editor', [
          'height'  => '250px',
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
  echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.question_closed'), 'icon' => 'closed']);
endif; ?>