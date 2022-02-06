<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<h1 class="ml30"><?= Translate::get('categories'); ?></h1>

<div class="item-categories mb-block">
  <?php foreach (Config::get('web-root-categories') as  $cat) { ?>
    <div class="mb10">
      <a class="text-2xl block" href="<?= getUrlByName('web.dir.top', ['slug' => $cat['url']]); ?>">
        <?= $cat['title']; ?>
      </a>
      <?php if (!empty($cat['sub'])) { ?>
        <?php foreach ($cat['sub'] as $sub) { ?>
          <a class="pr10 text-sm black inline" href="<?= getUrlByName('web.dir.top', ['slug' => $sub['url']]); ?>">
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
    <p>
      <?= num_word($data['count'], Translate::get('num-website'), false); ?>:
      <?= $data['count']; ?>
      <span class="right mr30">
        <a class="<?php if ($data['sheet'] == 'web.all') { ?>bg-gray-100 p5 gray-600 <?php } ?>mr20" href="<?= getUrlByName('web.all'); ?>">
          <?= Translate::get('by.date'); ?>
        </a>
        <a class="<?php if ($data['sheet'] == 'web.top') { ?>bg-gray-100 p5 gray-600 <?php } ?>" href="<?= getUrlByName('web.top'); ?>">
          TOP
        </a>
      </span>
    </p>

    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user]); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName($data['sheet'])); ?>
  </main>
  <aside class="col-span-3 mb-col-12 mb-none">
    <div class="box-yellow text-sm mt15"><?= Translate::get('directory.info'); ?></div>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>