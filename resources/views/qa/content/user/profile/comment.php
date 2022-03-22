<div>
  <?= Tpl::import('/content/user/profile/header', ['user' => $user, 'data' => $data]); ?>

  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex-white">
        <p class="m0"><?= Translate::get('comments'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?php if (!empty($data['comments'])) { ?>
        <?= Tpl::import('/content/comment/comment', ['answer' => $data['comments'], 'user' => $user]); ?>
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
<?= Tpl::import('/_block/js-msg-flag', ['uid' => $user['id']]); ?>