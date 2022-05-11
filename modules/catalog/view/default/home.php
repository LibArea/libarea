<?= includeTemplate('/view/default/header', ['data' => $data, 'meta' => $meta]); ?>

<?php if (!UserData::checkActiveUser()) : ?>
  <div class="mb-none">
    <center>
      <h1><?= __('web.main_title'); ?></h1>
      <p class="max-w780"><?= __('web.banner_info'); ?>.</p>
    </center>
  </div>
<?php endif; ?>
<div class="item-categories">
  <?php foreach (config('catalog/home-categories') as $cat) : ?>
    <div class="categories-telo">
      <a class="text-2xl block" href="<?= url('web.dir', ['grouping' => 'all', 'slug' => $cat['url']]); ?>">
        <?= $cat['title']; ?>
      </a>
      <?php if (!empty($cat['sub'])) : ?>
        <?php foreach ($cat['sub'] as $sub) : ?>
          <a class="pr10 text-sm black mb-none" href="<?= url('web.dir', ['grouping' => 'all', 'slug' => $sub['url']]); ?>">
            <?= $sub['title']; ?>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
      <?php if (!empty($cat['help'])) : ?>
        <div class="text-sm gray-600 mb-none"><?= $cat['help']; ?>...</div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

<div id="contentWrapper">
  <main class="col-two">
    <h2 class="inline mb10"><?= __('web.' . $data['sheet']); ?></h2>
    <?php if (!empty($data['items'])) : ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'screening' => $data['screening']]); ?>
    <?php else : ?>
      <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no_website'), 'icon' => 'bi-info-lg']); ?>
    <?php endif; ?>
  </main>
  <aside>
    <div class="box bg-yellow text-sm max-w300"><?= __('web.sidebar_info'); ?></div>
    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box text-sm bg-violet mt15">
        <h3 class="uppercase-box"><?= __('web.menu'); ?></h3>
        <ul class="menu">
          <?= includeTemplate('/view/default/_block/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>
<?= includeTemplate('/view/default/footer'); ?>