<main>
  <div class="box">
    <h1 class="m0"><?= __('app.domain') . ': ' . $data['site']; ?> <sup class="gray-600"><?= $data['count']; ?></sup></h1>
  </div>
  <?= insert('/content/post/post-card', ['data' => $data]); ?>
  <?= Html::pagination($data['pNum'], $data['pagesCount'], null, url('domain', ['domain' => $data['site']])); ?>
</main>
<aside>
  <div class="sticky top-sm">
    <div class="box">
      <?php if (!empty($data['list'])) : ?>
        <h4 class="uppercase-box"><?= __('app.domains'); ?></h4>
        <?php foreach ($data['list'] as  $domain) : ?>
          <a class="text-sm gray block mb5" href="<?= url('domain', ['domain' => $domain['post_url_domain']]); ?>">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#link"></use>
            </svg> <?= $domain['post_url_domain']; ?>
          </a>
        <?php endforeach; ?>
      <?php else : ?>
        <p><?= __('app.no_content'); ?>...</p>
      <?php endif; ?>
    </div>
  </div>
</aside>