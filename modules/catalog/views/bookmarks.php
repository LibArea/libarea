<?= insertTemplate('header', ['meta' => $meta]); ?>

<div id="contentWrapper" class="wrap justify-between">
  <main>
    <div class="box">
      <h1 class="m0 mb15">
        <?= __($data['sheet'] . '_view'); ?>
        <?php if ($data['count'] != 0) : ?><sup class="gray-600 text-sm"><?= $data['count']; ?></sup><?php endif; ?>
      </h1>

      <?php if (!empty($data['items'])) : ?>
        <?= insertTemplate('/item/card', ['data' => $data, 'user' => $container->user()->get(), 'delete_fav' => 'yes', 'screening' => $data['screening'], 'sort' => false]); ?>
      <?php else : ?>
        <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no_bookmarks'), 'icon' => 'info']); ?>
      <?php endif; ?>

      <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url($data['sheet'])); ?>
    </div>
  </main>
  <aside>
    <div class="box bg-beige mt15"><?= __('web.bookmarks'); ?>.</div>
    <?php if ($container->user()->active()) : ?>
      <div class="box text-sm bg-lightgray mt15">
        <h4 class="uppercase-box"><?= __('web.menu'); ?></h4>
        <ul class="menu">
          <?= insertTemplate('/navigation/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>

<?= insertTemplate('footer'); ?>