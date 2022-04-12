<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<main class="main-search col-two">
  <?php foreach ($data['tags'] as $key => $tag) : ?>

    <?php if ($data['type'] == 'post') : ?>
      <a class="mr20" href="<?= getUrlByName('topic', ['slug' => $tag['facet_slug']]); ?>">
      <?php else : ?>
        <a class="mr20" href="<?= getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $tag['facet_slug']]); ?>">
        <?php endif; ?>

        <?= Html::image($tag['facet_img'], $tag['facet_title'], 'img-base', 'logo', 'max'); ?>
        <?= $tag['facet_title']; ?>
        </a>

      <?php endforeach; ?>

      <?php if ($data['result']) : ?>
        <h3 class="mt20 mb20">
          <?= __('results.search'); ?> <?= $data['count']; ?>
        </h3>

        <?= includeTemplate('/view/default/list', ['result' => $data['result'], 'type' => $data['type']]); ?>

        <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/search'); ?>
      <?php else : ?>
        <?= includeTemplate('/view/default/no-result', ['query' => $data['query']]); ?>
      <?php endif; ?>
</main>

<?= includeTemplate('/view/default/footer'); ?>