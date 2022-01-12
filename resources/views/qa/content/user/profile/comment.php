<?= import('/content/user/profile/header', ['uid' => $uid, 'data' => $data]); ?>

<?= import('/content/user/profile/sidebar', ['uid' => $uid, 'data' => $data]); ?>

<main class="col-span-8 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get('comments'); ?> <b><?= $data['user']['user_login']; ?></b></p>
  </div>
  <?php if (!empty($data['comments'])) { ?>
    <?php foreach ($data['comments'] as $comm) { ?>
      <div class="bg-white br-rd5 mt15 br-box-gray p15">
        <div class="text-sm gray mb5">
          <a class="gray" href="<?= getUrlByName('profile', ['login' => $comm['user_login']]); ?>">
            <?= user_avatar_img($comm['user_avatar'], 'max', $comm['user_login'], 'w20 h20 mr5'); ?>
            <?= $comm['user_login']; ?>
          </a>
          <span class="mr5 ml5 gray-400 lowercase">
            <?= $comm['date']; ?>
          </span>
        </div>
        <a class="mr5 mb5 block" href="<?= getUrlByName('post', ['id' => $comm['post_id'], 'slug' => $comm['post_slug']]); ?>">
          <?= $comm['post_title']; ?>
        </a>
        <div>
          <?= $comm['comment_content']; ?>
        </div>
        <div class="hidden gray">
          <?= votes($uid['user_id'], $comm, 'comment', 'ps', 'mr5'); ?>
        </div>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('profile', ['login' => $data['user']['user_login']]) . '/comments'); ?>

  <?php } else { ?>
    <?= no_content(Translate::get('there are no comments'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
</div>
<?= import('/_block/wide-footer', ['uid' => $uid]); ?>