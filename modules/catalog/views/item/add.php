<?= insertTemplate('header', ['meta' => $meta]); ?>

<div id="contentWrapper" class="wrap">
  <main>
    <div class="box">
      <?= insert('/_block/navigation/breadcrumbs', [
        'list' => [
          [
            'name' => __('web.catalog'),
            'link' => url('web')
          ], [
            'name' => __('web.add_website'),
            'link' => 'red'
          ],
        ]
      ]); ?>

      <form action="<?= url('add.item', method: 'post'); ?>" class="max-w-md" method="post">
        <?= $container->csrf()->field(); ?>
        <?= insertTemplate('/form/category', ['category' => $data['category'], 'action' => 'add']); ?>
        <?= insertTemplate('/form/add-website'); ?>
        <?= Html::sumbit(__('web.add')); ?>
      </form>
    </div>
  </main>
  <aside>
    <div class="box shadow text-sm">
      <h4 class="uppercase-box"><?= __('web.help'); ?></h4>
      <?= __('web.data_help'); ?>
      <div>
  </aside>
</div>

<script src="/assets/js/tag/tagify.min.js"></script>
<link rel="stylesheet" href="/assets/js/tag/tagify.css" type="text/css">

<?= insertTemplate('footer'); ?>