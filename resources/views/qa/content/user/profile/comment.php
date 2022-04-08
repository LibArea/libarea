<div>
  <?= Tpl::insert('/content/user/profile/header', ['user' => $user, 'data' => $data]); ?>

  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex-white">
        <p class="m0"><?= Translate::get('comments'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?php if (!empty($data['comments'])) { ?>
        <?= Tpl::insert('/content/comment/comment', ['answer' => $data['comments'], 'user' => $user]); ?>
        <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/@' . $data['profile']['login'] . '/comments'); ?>
      <?php } else { ?>
        <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no.comments'), 'icon' => 'bi-info-lg']); ?>
      <?php } ?>
    </main>
    <aside>
      <?= Tpl::insert('/content/user/profile/sidebar', ['user' => $user, 'data' => $data]); ?>
    </aside>
  </div>
</div>
<?= Tpl::insert('/_block/js-msg-flag', ['uid' => $user['id']]); ?>