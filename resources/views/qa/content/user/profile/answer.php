<div>
  <?= Tpl::import('/content/user/profile/header', ['user' => $user, 'data' => $data]); ?>
  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex-white">
        <p class="m0"><?= Translate::get('answers'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?php if (!empty($data['answers'])) { ?>
        <?= Tpl::import('/content/answer/answer', ['data' => $data, 'user' => $user]); ?>
        <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/@' . $data['profile']['login'] . '/answers'); ?>
      <?php } else { ?>
        <?= Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no.answers'), 'icon' => 'bi-info-lg']); ?>
      <?php } ?>
    </main>
    <aside>
      <?= Tpl::import('/content/user/profile/sidebar', ['user' => $user, 'data' => $data]); ?>
    </aside>
  </div>
</div>
<?= Tpl::import('/_block/js-msg-flag', ['uid' => $user['id']]); ?>