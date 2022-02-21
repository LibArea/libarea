<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<div class="box-white max-w640">
  <form action="<?= getUrlByName('admin.navigation.create'); ?>" method="post">
    <?= csrf_field() ?>

    <?php if ($data['parent'] == 1) { ?>
      <fieldset>
        <label for="nav_parent"><?= Translate::get('parent'); ?></label>
        <select name="nav_parent">
          <?php foreach (Modules\Admin\App\Navigation::parents() as $value) { ?>
            <option <?php if ($value['nav_id'] == $data['menu']['nav_id']) { ?>selected<?php } ?> value="<?= $value['nav_id']; ?>">
              <?= Translate::get($value['nav_name']); ?>
            </option>
          <?php } ?>
        </select>
      </fieldset>
    <?php } else { ?>
      <input type="hidden" value="0" name="nav_parent">
    <?php } ?>

    <fieldset>
      <label for="nav_name"><?= Translate::get('name'); ?></label>
      <input type="text" required="" minlength="3" maxlength="40" name="nav_name">
      <div class="help">3 - 40 (english) <?= Translate::get('characters'); ?></div>
    </fieldset>
    
    <fieldset>
      <label for="nav_url_routes"><?= Translate::get('URL'); ?></label>
      <input type="text" required="" minlength="3" maxlength="60" name="nav_url_routes">
      <div class="help">3 - 60 (english) <?= Translate::get('characters'); ?></div>
    </fieldset>

    <?= sumbit(Translate::get('add')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>