<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<div id="fetch" class="col-span-2 mb-none">
  <div id="find"></div>
</div>
<main class="ml20">
  <div class="mb10">
    <?php foreach ($data['tags'] as $key => $facet) { ?>
      <?php if ($facet['facet_type'] == 'category') { ?>
        <a class="box-flex" href="<?= getUrlByName('web.dir', ['slug' => $facet['facet_slug']]); ?>">
          <?= facet_logo_img($facet['facet_img'], 'max', $facet['facet_title'], 'img-base'); ?>
          <?= $facet['facet_title']; ?>
        </a>
        <sup class="gray mr15">x<?= $facet['facet_count']; ?></sup>
      <?php } ?>
    <?php } ?>
  </div>
  <?php if ($data['result']) { ?>
    <p class="mt0 text-xl">
      <?= Translate::get('results.search'); ?> <?= $data['count']; ?>
    </p>

    <?php foreach ($data['result'] as  $item) { ?>
      <div class="mb20 gray max-w780">
        <a class="text-xl" href="<?= getUrlByName('web.website', ['slug' => $item['item_url_domain']]); ?>">
          <?= $item['title']; ?>
        </a>
        <?= html_category($item['facet_list'], 'category', 'cat', 'mr15 tags'); ?>
        <p><?= $item['content']; ?></p>
        <div class="text-sm">
          <a class="green-600" href="<?= $item['item_url']; ?>">
            <?= website_img($item['item_url_domain'], 'favicon', $item['item_url_domain'], 'favicons mr5'); ?>
            <?= $item['item_url_domain']; ?>
          </a>
          <div class="right gray-400">
            <i class="bi bi-heart mr5"></i> <?= $item['item_votes']; ?>
            <i class="bi bi-eye mr5 ml15"></i> <?= $item['item_count']; ?>
          </div>
        </div>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/search'); ?>

  <?php } else { ?>
    <p><?= Translate::get('no.search.results'); ?></p>
    <a class="mb20 block" href="/"><?= Translate::get('to main'); ?>...</a>
  <?php } ?>
</main>

<?= includeTemplate('/view/default/footer'); ?>