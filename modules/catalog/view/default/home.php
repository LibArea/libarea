<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<?php if ($user['id'] == 0) { ?>
  <div class="mb-none">
    <center>
      <h1><?= __('web.home.title'); ?></h1>
      <p class="max-w780"><?= __('web.banner.info'); ?>.</p>
    </center>
  </div>
<?php } ?>
<div class="item-categories">
  <?php foreach (Config::get('catalog/home-categories') as $cat) { ?>
    <div class="categories-telo">
      <a class="text-2xl block" href="<?= getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $cat['url']]); ?>">
        <?= $cat['title']; ?>
      </a>
      <?php if (!empty($cat['sub'])) { ?>
        <?php foreach ($cat['sub'] as $sub) { ?>
          <a class="pr10 text-sm black mb-none" href="<?= getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $sub['url']]); ?>">
            <?= $sub['title']; ?>
          </a>
        <?php } ?>
      <?php } ?>
      <?php if (!empty($cat['help'])) { ?>
        <div class="text-sm gray-600 mb-none"><?= $cat['help']; ?>...</div>
      <?php } ?>
    </div>
  <?php } ?>
</div>

<div id="contentWrapper">
  <main class="col-two">
    <?= includeTemplate('/view/default/nav', ['data' => $data, 'uid' => $user['id']]); ?>
    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user, 'screening' => $data['screening']]); ?>
    <?php } else { ?>
      <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
    <?php } ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/web/cat'); ?>
  </main>
  <aside>
    <div class="box-yellow text-sm max-w300"><?= __('directory.info'); ?></div>
    <?php if (UserData::checkActiveUser()) { ?>
      <div class="box-white text-sm bg-violet-50 mt15">
        <h3 class="uppercase-box"><?= __('menu'); ?></h3>
        <ul class="menu">
          <?= includeTemplate('/view/default/_block/add-site', ['user' => $user, 'data' => $data]); ?>
          <?= Tpl::insert('/_block/navigation/menu', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('catalog/menu.user')]); ?>
        </ul>
      </div>
    <?php } ?>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>