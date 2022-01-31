<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

  <?php if (UserData::checkAdmin()) { ?>
    <div class="mr15 ml15">
      <a class="right" href="<?= getUrlByName('web.deleted'); ?>">
        <?= Translate::get('deleted'); ?>
      </a>
      <h1><?= Translate::get('categories'); ?></h1>
    </div>
  <?php } ?>
   
  <div class="flex mb20 p15 bg-yellow-50">
    <?php foreach (Config::get('web-root-categories') as  $cat) { ?>
      <div class="mr60">
        <a class="pr20 underline-hover text-2xl block " href="<?= getUrlByName('web.topic', ['slug' => $cat['url']]); ?>">
          <?= $cat['title']; ?>
        </a>
        <?php if (!empty($cat['sub'])) { ?>
          <?php foreach ($cat['sub'] as $sub) { ?>
            <a class="pr10 text-sm black inline" href="<?= getUrlByName('web.topic', ['slug' => $sub['url']]); ?>">
              <?= $sub['title']; ?>
            </a>
          <?php } ?>
        <?php } ?>
        <?php if (!empty($cat['help'])) { ?>
          <div class="text-sm gray-400 mt5"><?= $cat['help']; ?>...</div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
<div class="grid grid-cols-12 gap-4">
  <main class="col-span-9 mb-col-12 ml15">
  <div class="box-flex max-w780">
    <div>
    <?= num_word($data['count'], Translate::get('num-website'), false); ?>: 
    <?= $data['count']; ?>
    </div>
    <div>
    <a class="<?php if ( $data['sheet'] == 'web.all') { ?>bg-gray-100 p5 gray-600 <?php } ?>mr20" href="<?= getUrlByName('web'); ?>">
      <?= Translate::get('by.date'); ?>
    </a>
    <a class="<?php if ( $data['sheet'] == 'web.top') { ?>bg-gray-100 p5 gray-600 <?php } ?>" href="<?= getUrlByName('web.top'); ?>">
      TOP
    </a>
    </div>
  </div>

  <?php if (!empty($data['items'])) { ?>
    <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user]); ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>

  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName($data['sheet'])); ?>
</main>
<aside class="col-span-3 mb-col-12 relative mb-none">
  <?= Translate::get('being.developed'); ?>
</aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>