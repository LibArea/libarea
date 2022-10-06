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
        <label for="count_like_feed"><?= __('admin.count_like_feed'); ?></label>
        <input value="<?= $settings['count_like_feed']; ?>" type="text" required name="count_like_feed">
        <div class="help">> 6 <?= __('app.characters'); ?></div>
      </fieldset>
      
      <fieldset>
        <label for="type_post_feed"><?= __('admin.type_post_feed'); ?></label>
        <input value="<?= $settings['type_post_feed']; ?>" type="text" required name="type_post_feed">
        <div class="help">> 6 <?= __('app.characters'); ?></div>
      </fieldset>
      
      <?= Html::sumbit(__('app.edit')); ?>
  </form>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>