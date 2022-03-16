<div>
  <?= Tpl::import('/content/user/profile/header', ['user' => $user, 'data' => $data]); ?>

  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex-white">
        <p class="m0"><?= Translate::get('answers'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?php if (!empty($data['answers'])) { ?>
        <?php foreach ($data['answers'] as $answer) { ?>
          <div class="box-white">
            <div class="text-sm mb5">
              <a class="gray" href="<?= getUrlByName('profile', ['login' => $answer['login']]); ?>">
                <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'ava-sm'); ?>
                <?= $answer['login']; ?>
              </a>
              <span class="mr5 ml5 gray-400 lowercase">
                <?= $answer['date']; ?>
              </span>
            </div>
            <a class="mr5 block" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>">
              <?= $answer['post_title']; ?>
            </a>
            <div class="answ-telo">
              <?= $answer['content']; ?>
            </div>
            <div class="hidden gray">
              <?= votes($user['id'], $answer, 'answer', 'ps', 'mr5'); ?>
            </div>
          </div>
        <?php } ?>

        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/@' . $data['profile']['login'] . '/answers'); ?>

      <?php } else { ?>
        <?= no_content(Translate::get('no.answers'), 'bi bi-info-lg'); ?>
      <?php } ?>
    </main>
    <aside>
      <?= Tpl::import('/content/user/profile/sidebar', ['user' => $user, 'data' => $data]); ?>
    </aside>
  </div>

</div>