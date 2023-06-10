<div id="contentWrapper" class="wrap wrap-max">
  <main class="w-100">
    <a class="text-sm" href="<?= url('web'); ?>"><< <?= __('web.catalog'); ?></a> 
    <span class="gray-600">/ <?= __('web.' . $data['sheet']); ?></span>
    <h2 class="m0 mb10"><?= __('web.' . $data['sheet']); ?></h2>
    <?php if (!empty($data['items'])) : ?>
      <?= insert('/content/item/item-card', ['data' => $data, 'sort' => 'all']); ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no_website'), 'icon' => 'info']); ?>
    <?php endif; ?>
  </main>
  <aside>
    <div class="box bg-beige max-w300"><?= __('web.sidebar_info'); ?></div>
    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box bg-lightgray max-w300">
        <h4 class="uppercase-box"><?= __('web.menu'); ?></h4>
        <ul class="menu">
          <?= insert('/_block/navigation/item/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>