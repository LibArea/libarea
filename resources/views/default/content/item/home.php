<?php if (!UserData::checkActiveUser()) : ?>
  <div class="banner mt5 mb-none">
    <h1><?= __('web.main_title'); ?></h1>
    <p><?= __('web.banner_info'); ?>.</p>
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
          <a class="mr10 text-sm black mb-none" href="<?= url('web.dir', ['grouping' => 'all', 'slug' => $sub['url']]); ?>">
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
  <main>
    <h2 class="inline mb10"><?= __('web.' . $data['sheet']); ?></h2>
    <?php if (!empty($data['items'])) : ?>
      <?= insert('/content/item/site', ['data' => $data, 'screening' => $data['screening']]); ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no_website'), 'icon' => 'info']); ?>
    <?php endif; ?>
  </main>
  <aside>
    <div class="box bg-beige max-w300"><?= __('web.sidebar_info'); ?></div>
    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box bg-lightgray max-w300">
        <h3 class="uppercase-box"><?= __('web.menu'); ?></h3>
        <ul class="menu">
          <?= insert('/_block/navigation/item/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>