<?php $n = 0;
foreach ($data['answers'] as $answer) :
  $n++;
  $post_url = url('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]);
?>

  <?php if ($answer['answer_is_deleted'] == 0) : ?>
    <?php if ($n != 1) : ?><div class="br-top-dotted mt10 mb10"></div><?php endif; ?>
    <ol class="list-none">
      <li class="content_tree" id="answer_<?= $answer['answer_id']; ?>">
        <div class="content-body">
          <div class="flex text-sm">
            <a class="gray-600" href="<?= url('profile', ['login' => $answer['login']]); ?>">
              <?= Html::image($answer['avatar'],  $answer['login'], 'img-sm', 'avatar', 'small'); ?>
              <span class="mr5 ml5">
                <?= $answer['login']; ?>
              </span>
            </a>
            <?php if ($answer['post_user_id'] == $answer['answer_user_id']) : ?>
              <span class="sky mr5 ml0"><i class="bi-mic text-sm"></i></span>
            <?php endif; ?>
            <span class="mr5 ml5 gray-600 lowercase">
              <?= Html::langDate($answer['date']); ?>
            </span>
            <?php if (empty($answer['edit'])) : ?>
              <span class="mr5 ml10 gray-600">
                (<?= __('app.ed'); ?>.)
              </span>
            <?php endif; ?>
            <a rel="nofollow" class="gray-600 mr5 ml10" href="<?= $post_url; ?>#answer_<?= $answer['answer_id']; ?>"><i class="bi-hash"></i></a>
            <?= insert('/_block/show-ip', ['ip' => $answer['answer_ip'], 'publ' => $answer['answer_published']]); ?>
          </div>
          <div class="content-body">
            <?= Content::text($answer['content'], 'text'); ?>
          </div>
        </div>
        <div class="flex text-sm">
          <?= Html::votes($answer, 'answer', 'ps', 'bi-heart mr5'); ?>

          <?php if ($answer['post_closed'] == 0) : ?>
            <?php if ($answer['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
              <a data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray-600 mr5 ml10"><?= __('app.reply'); ?></a>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (Access::author('answer', $answer['answer_user_id'], $answer['date'], 30) === true) : ?>
            <?php if ($answer['answer_after'] == 0 || UserData::checkAdmin()) : ?>
              <a class="editansw gray-600 mr10 ml10" href="<?= url('content.edit', ['type' => 'answer', 'id' => $answer['answer_id']]); ?>">
                <?= __('app.edit'); ?>
              </a>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (UserData::checkAdmin()) : ?>
            <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray-600 ml10 mr10">
              <?= __('app.remove'); ?>
            </a>
          <?php endif; ?>

          <?php if (UserData::getUserId() != $answer['answer_user_id'] && UserData::getRegType(config('trust-levels.tl_stop_report'))) : ?>
            <a data-post_id="<?= $answer['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray-600 ml15">
              <i title="<?= __('app.report'); ?>" class="bi-flag"></i>
            </a>
          <?php endif; ?>
        </div>
        <div data-insert="<?= $answer['answer_id']; ?>" id="insert_id_<?= $answer['answer_id']; ?>" class="none"></div>
      </li>
    </ol>

  <?php else : ?>

    <?php if (UserData::checkAdmin()) : ?>
      <ol class="bg-red-200 text-sm hidden p15 mb10 list-none">
        <li class="content_tree" id="comment_<?= $answer['answer_id']; ?>">
          <?= Content::text($answer['content'], 'text'); ?>
          <?= __('app.answer'); ?> — <?= $answer['login']; ?>
          <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action right">
            <span><?= __('app.recover'); ?></span>
          </a>
        </li>
      </ol>
    <?php else : ?>
      <div class="gray-600 p10 text-sm">
        ~ <?= __('app.content_deleted', ['name' => __('app.comment')]); ?>...
      </div>
    <?php endif; ?>

  <?php endif; ?>
<?php endforeach; ?>