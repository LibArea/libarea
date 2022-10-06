<?= includeTemplate('/view/default/setting/nav', ['data' => $data, 'meta' => $meta]); 
foreach ($data['settings'] as $val)
{
  $settings[$val['val']] = $val['value'];
}

?>

<div>
  <form class="max-w780" action="<?= url('admin.setting.change'); ?>" method="post">
    <?= csrf_field() ?>
      <fieldset>
        <label for="url">URL</label>
        <input minlength="6" value="<?= $settings['url']; ?>" type="text" required name="url">
        <div class="help">> 6 <?= __('app.characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="email">email</label>
        <input minlength="6" value="<?= $settings['email']; ?>" type="email" required name="email">
        <div class="help">> 6 <?= __('app.characters'); ?></div>
      </fieldset>
 
      <fieldset>
        <label for="name"><?= __('admin.name'); ?></label>
        <input minlength="6" value="<?= $settings['name']; ?>" type="text" required name="name">
        <div class="help">> 6 <?= __('app.characters'); ?></div>
      </fieldset>
      
      <fieldset>
        <label for="name"><?= __('admin.title'); ?></label>
        <input minlength="6" value="<?= $settings['title']; ?>" type="text" required name="title">
        <div class="help">> 6 <?= __('app.characters'); ?></div>
      </fieldset>
      
      <fieldset>
        <label for="img_path"><?= __('admin.img_path'); ?></label>
        <input minlength="6" value="<?= $settings['img_path']; ?>" type="text" required name="img_path">
        <div class="help">> 6 <?= __('app.characters'); ?></div>
      </fieldset>
      
      <fieldset>
        <label for="img_path"><?= __('admin.img_path_web'); ?></label>
        <input minlength="6" value="<?= $settings['img_path_web']; ?>" type="text" required name="img_path_web">
        <div class="help">> 6 <?= __('app.characters'); ?></div>
      </fieldset>
      
      <fieldset><br></fieldset>
      
      <fieldset>
        <label for="banner_title"><?= __('admin.banner_title'); ?></label>
        <input minlength="6" value="<?= $settings['banner_title']; ?>" type="text" required name="banner_title">
        <div class="help">> 6 <?= __('app.characters'); ?></div>
      </fieldset>      
      
      <fieldset>
        <label for="banner_desc"><?= __('admin.banner_desc'); ?></label>
        <textarea type="banner_desc" rows="3" name="banner_desc"><?= $settings['banner_desc']; ?></textarea>
        <div class="help">ddddddddddddddddddd</div>
      </fieldset>
      
      <?= Html::sumbit(__('app.edit')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>