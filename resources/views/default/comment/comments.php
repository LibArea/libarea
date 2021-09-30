<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('home'), null,  null, lang('all comments')); ?>
  </div>
  <?php if (!empty($data['comments'])) { ?>
    <?php foreach ($data['comments'] as $comment) { ?>
      <div class="bg-white br-rd-5 mt15 border-box-1 pt5 pr15 pb5 pl15">
        <?php if ($comment['comment_is_deleted'] == 0) { ?>
          <div class="size-14">
            <a class="gray" href="<?= getUrlByName('user', ['login' => $comment['user_login']]); ?>">
              <?= user_avatar_img($comment['user_avatar'], 'small', $comment['user_login'], 'ava'); ?>
              <span class="mr5 ml5">
                <?= $comment['user_login']; ?>
              </span>
            </a>
            <span class="gray lowercase"><?= $comment['date']; ?></span>
          </div>
          <a href="<?= getUrlByName('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>#comment_<?= $comment['comment_id']; ?>">
            <?= $comment['post_title']; ?>
          </a>
          <div class="comm-telo-body size-15 mt5 mb5">
            <?= $comment['comment_content']; ?>
          </div>
          <div class="hidden gray">
            + <?= $comment['comment_votes']; ?>
          </div>
        <?php } else { ?>
          <div class="bg-red-300 mb20">
            <div class="voters"></div>
            ~ <?= lang('comment deleted'); ?>
          </div>
        <?php } ?>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>

  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'there are no comments']); ?>
  <?php } ?>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('comments-desc')]); ?>