<?= Tpl::insert('header', ['user' => $user, 'data' => $data, 'meta' => $meta]); ?>
<main class="col-span-12 mb-col-12">
  <div class="pt5 mr15 pb5 ml15">
    <?php if (UserData::checkAdmin()) { ?>
      <a title="<?= Translate::get('deleted'); ?>" class="right mt5 ml15" href="<?= getUrlByName('web.deleted'); ?>">
        <?= Translate::get('deleted'); ?>
      </a>
      <a title="<?= Translate::get('add'); ?>" class="right mt5" href="<?= getUrlByName('site.add'); ?>">
        <i class="bi bi-plus-lg middle"></i>
      </a>
    <?php } ?>
    <h1 class="mt0 mb10 text-2xl font-normal"><?= Translate::get('site.directory'); ?></h1>
  </div>
  <div class="flex mb20 pt10 pr15 pb10 pl15 bg-yellow-50 flex-auto">
    <?php foreach (Config::get('web-root-categories') as  $cat) { ?>
      <div class="mr60">
        <a class="pt5 pr10 mr60 dark-gray-300 underline-hover text-2xl block " title="<?= $cat['title']; ?>" href="<?= getUrlByName('web.topic', ['slug' => $cat['url']]); ?>">
          <?= $cat['title']; ?>
        </a>
        <?php if (!empty($cat['sub'])) { ?>
          <?php foreach ($cat['sub'] as $sub) { ?>
            <a class="pr10 pb5 text-sm black inline" title="<?= $sub['title']; ?>" href="<?= getUrlByName('web.topic', ['slug' => $sub['url']]); ?>">
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
  <div class="pt5 mr15 pb5 ml15">
    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user]); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <div class="pl10">
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('web')); ?>
  </div>
</main>
</div>
<?= includeTemplate('/view/default/footer'); ?>