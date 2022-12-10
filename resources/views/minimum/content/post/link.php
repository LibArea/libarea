<main class="w-100">
  <div class="box">
    <h1 class="m0"><?= __('app.domain') . ': ' . $data['site']; ?></h1>
  </div>

  <?= insert('/content/post/post-card', ['data' => $data]); ?>
  <?= Html::pagination($data['pNum'], $data['pagesCount'], null, url('domain', ['domain' => $data['site']])); ?>
</main>