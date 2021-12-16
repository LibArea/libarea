<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
  </div>
  <?php if (!empty($data['comments'])) { ?>
    <?php foreach ($data['comments'] as $comment) { ?>
      <div class="bg-white br-rd5 mt15 br-box-gray p15">
        <?php if ($comment['comment_is_deleted'] == 0) { ?>
          <div class="size-14">
            <a class="gray" href="<?= getUrlByName('user', ['login' => $comment['user_login']]); ?>">
              <?= user_avatar_img($comment['user_avatar'], 'small', $comment['user_login'], 'w18'); ?>
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
            ~ <?= Translate::get('comment deleted'); ?>
          </div>
        <?php } ?>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>

  <?php } else { ?>
    <?= no_content(Translate::get('there are no comments'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
<?= includeTemplate('/_block/sidebar/lang', ['lang' => Translate::get('comments-desc')]); ?>