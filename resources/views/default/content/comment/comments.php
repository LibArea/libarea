<div class="sticky col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.left'),
      ); ?>
</div>
<main class="col-span-7 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
  </div>
  <?php if (!empty($data['comments'])) { ?>
    <?php foreach ($data['comments'] as $comment) { ?>
      <div class="bg-white br-rd5 mt15 br-box-gray p15">
        <?php if ($comment['comment_is_deleted'] == 0) { ?>
          <div class="text-sm mb5">
            <a class="gray" href="<?= getUrlByName('user', ['login' => $comment['user_login']]); ?>">
              <?= user_avatar_img($comment['user_avatar'], 'small', $comment['user_login'], 'w18'); ?>
              <span class="mr5 ml5">
                <?= $comment['user_login']; ?>
              </span>
            </a>
            <span class="gray-400 lowercase"><?= $comment['date']; ?></span>
          </div>
          <a href="<?= getUrlByName('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>#comment_<?= $comment['comment_id']; ?>">
            <?= $comment['post_title']; ?>
          </a>
          <div>
            <?= $comment['comment_content']; ?>
          </div>
          <div class="hidden gray">
            <?= votes($uid['user_id'], $comment, 'comment', 'mr5'); ?>
          </div>
        <?php } else { ?>
          <div class="bg-red-200 mb20">
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
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('comments-desc')]); ?>