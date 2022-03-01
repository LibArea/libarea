<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<div class="grid grid-cols-12 gap-4">
  <main>
    <h2 class="mb20">
      <?= Translate::get($data['sheet'] . '.view'); ?>
      <?php if ($data['count'] != 0) { ?><sup class="gray-400 text-sm"><?= $data['count']; ?></sup><?php } ?>
    </h2>

    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user, 'delete_fav' => 'yes', 'screening' => $data['screening']]); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no.bookmarks.sites'), 'bi bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName($data['sheet'])); ?>
  </main>
  <aside class="col-span-3 mb-none">
    <div class="box-yellow text-sm mt15"><?= Translate::get('web.bookmarks.info'); ?>.</div>
    <?php if (UserData::checkActiveUser()) { ?>
      <div class="box-white text-sm bg-violet-50 mt15">
        <h3 class="uppercase-box"><?= Translate::get('menu'); ?></h3>
        <ul class="menu">
          <?= includeTemplate('/view/default/_block/add-site', ['user' => $user, 'data' => $data]); ?>

          <?= tabs_nav(
            'menu',
            $data['sheet'],
            $user,
            $pages = Config::get('catalog/menu.user')
          ); ?>
        </ul>
      </div>
    <?php } ?>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>