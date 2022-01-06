<?= import('/content/user/profile/header', ['uid' => $uid, 'data' => $data]); ?>

<?= import('/content/user/profile/sidebar', ['uid' => $uid, 'data' => $data]); ?>

<main class="col-span-8 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get('answers'); ?> <b><?= $data['user']['user_login']; ?></b></p>
  </div>
  <?php if (!empty($data['answers'])) { ?>
    <?php foreach ($data['answers'] as $answer) { ?>
      <div class="bg-white br-rd5 br-box-gray p15">
        <div class="text-sm mb5">
          <a class="gray" href="<?= getUrlByName('profile', ['login' => $answer['user_login']]); ?>">
            <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18 mr5'); ?>
            <?= $answer['user_login']; ?>
          </a>
          <span class="mr5 ml5 gray-400 lowercase">
            <?= $answer['date']; ?>
          </span>
        </div>
        <a class="mr5 block" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>">
          <?= $answer['post_title']; ?>
        </a>
        <div>
          <?= $answer['content']; ?>
        </div>
        <div class="hidden gray">
          <?= votes($uid['user_id'], $answer, 'answer', 'ps', 'mr5'); ?>
        </div>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('profile', ['login' => $data['user']['user_login']]) . '/answers'); ?>

  <?php } else { ?>
    <?= no_content(Translate::get('no answers'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
</div>
<?= import('/_block/wide-footer', ['uid' => $uid]); ?>