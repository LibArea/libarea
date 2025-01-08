<?= insertTemplate('/setting/nav', ['data' => $data, 'meta' => $meta]);
foreach ($data['settings'] as $val) {
  $settings[$val['val']] = $val['value'];
}

?>

<div>
  <form class="max-w-md" action="<?= url('admin.setting.edit', method: 'post'); ?>" method="post">
    <?= $container->csrf()->field(); ?>
    <fieldset>
      <label for="count_like_feed"><?= __('admin.count_like_feed'); ?></label>
      <input value="<?= $settings['count_like_feed']; ?>" type="text" required name="count_like_feed">
      <div class="help"><?= __('admin.count_like_feed_help'); ?></div>
    </fieldset>

    <fieldset>
      <label for="type_post_feed"><?= __('admin.type_post_feed'); ?></label>
      <select name="type_post_feed">
        <option <?php if ($settings['type_post_feed'] == 'card') { ?>selected<?php } ?> value="card">Card</option>
        <option <?php if ($settings['type_post_feed'] == 'classic') { ?>selected<?php } ?> value="classic">Classic</option>
      </select>
      <div class="help"><?= __('admin.type_post_feed_help'); ?></div>
    </fieldset>

    <?= Html::sumbit(__('admin.edit')); ?>
  </form>
</div>
</main>
<?= insertTemplate('footer'); ?>