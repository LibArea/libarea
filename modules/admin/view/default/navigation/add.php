<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <form action="<?= getUrlByName('admin.navigation.create'); ?>" method="post">
    <?= csrf_field() ?>

    <?php if ($data['parent'] == 1) { ?>
      <div class="mb20 max-w640">
        <label class="block" for="post_content"><?= Translate::get('parent'); ?></label>
        <select class="h40" name="nav_parent">
          <?php foreach (Modules\Admin\App\Navigation::parents() as $value) { ?>
            <option <?php if ($value['nav_id'] == $data['menu']['nav_id']) { ?>selected<?php } ?> value="<?= $value['nav_id']; ?>">
              <?= Translate::get($value['nav_name']); ?>
            </option>
          <?php } ?>
        </select>
      </div>
    <?php } else { ?>
      <input type="hidden" value="0" name="nav_parent">
    <?php } ?>

    <?= Tpl::import('/_block/form/field-input', [
      'data' => [
        [
          'title' => Translate::get('name'),
          'type' => 'text',
          'name' => 'nav_name',
          'min' => 3,
          'max' => 40,
          'help' => '3 - 40 (english) ' . Translate::get('characters'),
          'red' => 'red'
        ], [
          'title' => Translate::get('URL'),
          'type' => 'text',
          'name' => 'nav_url_routes',
          'min' => 3,
          'max' => 60,
          'help' => '3 - 60 (english) ' . Translate::get('characters'),
          'red' => 'red'
        ],
      ]
    ]); ?>

    <?= sumbit(Translate::get('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>