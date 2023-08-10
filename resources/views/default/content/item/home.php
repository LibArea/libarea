<?php if (!UserData::checkActiveUser()) : ?>
  <div class="banner mt5 mb-none">
    <h1><?= __('web.main_title'); ?></h1>
    <p><?= __('web.banner_info'); ?>.</p>
  </div>
<?php endif; ?>
<div class="item-categories mb-mt5">
  <?php foreach (config('catalog/home-categories') as $cat) : ?>
    <div class="categories-telo">
      <a class="text-2xl block" href="<?= url('category', ['sort' => 'all', 'slug' => $cat['url']]); ?>">
        <?= $cat['title']; ?>
      </a>
      <?php if (!empty($cat['sub'])) : ?>
        <div class="flex gap">
          <?php foreach ($cat['sub'] as $sub) : ?>
            <a class="text-sm black mb-none" href="<?= url('category', ['sort' => 'all', 'slug' => $sub['url']]); ?>">
              <?= $sub['title']; ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($cat['help'])) : ?>
        <div class="text-sm gray-600 mb-none"><?= $cat['help']; ?>...</div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

<div id="contentWrapper" class="wrap wrap-max justify-between">
  <main>
    <h2 class="m0 mb10"><?= __('web.new_sites'); ?></h2>
    <?php if (!empty($data['items'])) : ?>
      <?= insert('/content/item/item-card', ['data' => $data, 'sort' => 'all']); ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no_website'), 'icon' => 'info']); ?>
    <?php endif; ?>
  </main>
  <aside>
    <div class="box bg-beige"><?= __('web.sidebar_info'); ?></div>
    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box bg-lightgray">
        <h4 class="uppercase-box"><?= __('web.menu'); ?></h4>
        <ul class="menu">
          <?= insert('/_block/navigation/item/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>