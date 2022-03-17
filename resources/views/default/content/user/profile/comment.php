<div class="w-100">
  <?= Tpl::import('/content/user/profile/header', ['user' => $user, 'data' => $data]); ?>

  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex-white">
        <p class="m0"><?= Translate::get('comments'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?php if (!empty($data['comments'])) { ?>
        <?php foreach ($data['comments'] as $comm) { ?>
          <div class="bg-white br-rd5 mt15 br-box-gray p15">
            <div class="text-sm gray mb5">
              <a class="gray" href="<?= getUrlByName('profile', ['login' => $comm['login']]); ?>">
                <?= user_avatar_img($comm['avatar'], 'max', $comm['login'], 'ava-sm'); ?>
                <?= $comm['login']; ?>
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
              <?= votes($user['id'], $comm, 'comment', 'ps', 'mr5'); ?>
            </div>
          </div>
        <?php } ?>

        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/@' . $data['profile']['login'] . '/comments'); ?>

      <?php } else { ?>
        <?= no_content(Translate::get('no.comments'), 'bi bi-info-lg'); ?>
      <?php } ?>
    </main>
    <aside>
      <?= Tpl::import('/content/user/profile/sidebar', ['user' => $user, 'data' => $data]); ?>
    </aside>
  </div>
</div>