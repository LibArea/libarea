<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<main class="main-search col-two">
  <?php foreach ($data['tags'] as $key => $tag) { ?>
    <?php if ($data['type'] == 'post') { ?>
      <a class="mr20" href="<?= getUrlByName('topic', ['slug' => $tag['facet_slug']]); ?>">
      <?php } else { ?>
        <a class="mr20" href="<?= getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $tag['facet_slug']]); ?>">
        <?php } ?>
        <?= facet_logo_img($tag['facet_img'], 'max', $tag['facet_title'], 'img-base'); ?>
        <?= $tag['facet_title']; ?>
        </a>
      <?php } ?>

      <?php if ($data['result']) { ?>
        <h3 class="mt20 mb20">
          <?= Translate::get('results.search'); ?> <?= $data['count']; ?>
        </h3>

        <?= includeTemplate('/view/default/list', ['result' => $data['result'], 'type' => $data['type']]); ?>

        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/search'); ?>
      <?php } else { ?>
        <?= includeTemplate('/view/default/no-result', ['query' => $data['query']]); ?>
      <?php } ?>
</main>

<?= includeTemplate('/view/default/footer'); ?>