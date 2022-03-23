<main class="col-two">
  <h1 class="ml15"><?= Translate::get($data['type']); ?></h1>

  <?php if (!empty($data['comments'])) { ?>
    <?php foreach ($data['comments'] as $comment) { ?>
      <div class="bg-white br-rd5 mt15 p15">
        <?php if ($comment['comment_is_deleted'] == 0) { ?>
          <div class="text-sm mb5">
            <a class="gray" href="<?= getUrlByName('profile', ['login' => $comment['login']]); ?>">
              <?= Html::image($comment['avatar'], $comment['login'], 'ava-sm', 'avatar', 'small'); ?>
              <span class="mr5 ml5">
                <?= $comment['login']; ?>
              </span>
            </a>
            <span class="gray-600 lowercase"><?= Html::langDate($comment['date']); ?></span>
          </div>
          <a href="<?= getUrlByName('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>#comment_<?= $comment['comment_id']; ?>">
            <?= $comment['post_title']; ?>
          </a>
           <div class="content-body">
              <?= Content::text($comment['content'], 'text'); ?>
            </div>
          <div class="hidden gray">
            <?= Html::votes($user['id'], $comment, 'comment', 'ps', 'mr5'); ?>
          </div>
        <?php } else { ?>
          <div class="bg-red-200 mb20">
            ~ <?= sprintf(Translate::get('content.deleted'), Translate::get('comment')); ?>
          </div>
        <?php } ?>
      </div>
    <?php } ?>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>

  <?php } else { ?>
    <?= Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no.comments'), 'icon' => 'bi bi-info-lg']); ?>
  <?php } ?>
</main>
<aside>
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('comments-desc'); ?>
  </div>
</aside>
<?= Tpl::import('/_block/js-msg-flag', ['uid' => $user['id']]); ?>