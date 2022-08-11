<?php $n = 0;
foreach ($data['answers'] as $answer) :
  $n++;
  $post_url = url('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]);
?>

  <?php if ($answer['answer_is_deleted'] == 0) : ?>
    <?php if ($n != 1) : ?><div class="br-top-dotted mt10 mb10"></div><?php endif; ?>
    <ol class="list-none">
      <li class="content_tree box box-fon" id="answer_<?= $answer['answer_id']; ?>">
        <div class="content-body">
          <div class="flex text-sm gap">
            <a class="gray-600" href="<?= url('profile', ['login' => $answer['login']]); ?>">
              <?= Img::avatar($answer['avatar'],  $answer['login'], 'img-sm', 'small'); ?>
              <?= $answer['login']; ?>
            </a>
            <?php if ($answer['post_user_id'] == $answer['answer_user_id']) : ?>
              <svg class="icons icon-small sky">
                <use xlink:href="/assets/svg/icons.svg#mic"></use>
              </svg>
            <?php endif; ?>
            <span class="gray-600 lowercase">
              <?= Html::langDate($answer['date']); ?>
            </span>
            <?php if (empty($answer['edit'])) : ?>
              <span class="gray-600">
                (<?= __('app.ed'); ?>.)
              </span>
            <?php endif; ?>
            <a rel="nofollow" class="gray-600" href="<?= $post_url; ?>#answer_<?= $answer['answer_id']; ?>"><svg class="icons icon-small">
                <use xlink:href="/assets/svg/icons.svg#anchor"></use>
              </svg></a>
            <?= insert('/_block/admin-show-ip', ['ip' => $answer['answer_ip'], 'publ' => $answer['answer_published']]); ?>
          </div>
          <div class="content-body">
            <?= Content::text($answer['content'], 'text'); ?>
          </div>
        </div>
        <div class="flex text-sm gap">
          <?= Html::votes($answer, 'answer'); ?>

          <?php if ($answer['post_closed'] == 0) : ?>
            <?php if ($answer['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
              <a data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray-600"><?= __('app.reply'); ?></a>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (Access::author('answer', $answer['answer_user_id'], $answer['date'], 30) === true) : ?>
            <?php if ($answer['answer_after'] == 0 || UserData::checkAdmin()) : ?>
              <a class="editansw gray-600" href="<?= url('content.edit', ['type' => 'answer', 'id' => $answer['answer_id']]); ?>">
                <?= __('app.edit'); ?>
              </a>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (UserData::checkAdmin()) : ?>
            <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray-600">
              <?= __('app.remove'); ?>
            </a>
          <?php endif; ?>

          <?php if (UserData::getUserId() != $answer['answer_user_id'] && UserData::getRegType(config('trust-levels.tl_add_report'))) : ?>
            <a data-post_id="<?= $answer['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray-600">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#flag"></use>
              </svg>
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
      <div class="gray-600 p15 text-sm">
        ~ <?= __('app.content_deleted', ['name' => __('app.comment')]); ?>...
      </div>
    <?php endif; ?>

  <?php endif; ?>
<?php endforeach; ?>