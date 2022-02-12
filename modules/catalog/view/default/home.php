<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<?php if ($user['id'] == 0) { ?>
  <div class="box bg-white">
    <center>
      <h1><?= Translate::get('site.directory'); ?></h1>
      <p class="max-w780"><?= Translate::get('web.banner.info'); ?>.</p>
    </center>
  </div>
<?php } ?>

<div class="item-categories mb-block">
  <?php foreach (Config::get('web-root-categories') as  $cat) { ?>
    <div class="mb10">
      <a class="text-2xl block" href="<?= getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $cat['url']]); ?>">
        <?= $cat['title']; ?>
      </a>
      <?php if (!empty($cat['sub'])) { ?>
        <?php foreach ($cat['sub'] as $sub) { ?>
          <a class="pr10 text-sm black inline" href="<?= getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $sub['url']]); ?>">
            <?= $sub['title']; ?>
          </a>
        <?php } ?>
      <?php } ?>
      <?php if (!empty($cat['help'])) { ?>
        <div class="text-sm gray-400"><?= $cat['help']; ?>...</div>
      <?php } ?>
    </div>
  <?php } ?>
</div>

<div class="grid grid-cols-12 gap-4">
  <main class="col-span-9 mb-col-12 ml30">
    <?= includeTemplate('/view/default/nav', ['data' => $data, 'uid' => $user['id']]); ?>

    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user, 'screening' => $data['screening']]); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/web/cat'); ?>
  </main>
  <aside class="col-span-3 mb-col-12 mb-none">
    <div class="box-yellow text-sm mt15 max-w300 right"><?= Translate::get('directory.info'); ?></div>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>