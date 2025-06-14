<main>
	  <?= insert('/content/user/profile/header', ['data' => $data]); ?>
	
      <div class="mb15"><?= __('app.publications'); ?> <b><?= $data['profile']['login']; ?></b></div>

      <?= insert('/content/publications/choice', ['data' => $data]); ?>

      <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/contents'); ?>
    </main>
    <aside>
      <?= insert('/content/user/profile/sidebar', ['data' => $data]); ?>
    </aside>

