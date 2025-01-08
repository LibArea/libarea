<?= insertTemplate('/setting/nav', ['data' => $data, 'meta' => $meta]);
foreach ($data['settings'] as $val) {
  $settings[$val['val']] = $val['value'];
}

?>

<div>
  <form class="max-w-md" action="<?= url('admin.setting.edit', method: 'post'); ?>" method="post">
    <?= $container->csrf()->field(); ?>
    <fieldset>
      <label for="url">URL</label>
      <input value="<?= $settings['url']; ?>" type="text" required name="url">
      <div class="help"><?= __('admin.url_help'); ?></div>
    </fieldset>

    <fieldset>
      <label for="email">Email</label>
      <input value="<?= $settings['email']; ?>" type="email" required name="email">
      <div class="help"><?= __('admin.email_help'); ?></div>
    </fieldset>

    <fieldset>
      <label for="name"><?= __('admin.name'); ?></label>
      <input value="<?= $settings['name']; ?>" type="text" required name="name">
      <div class="help"><?= __('admin.name_help'); ?></div>
    </fieldset>

    <fieldset>
      <label for="name"><?= __('admin.title'); ?></label>
      <input value="<?= $settings['title']; ?>" type="text" required name="title">
      <div class="help"><?= __('admin.title_help'); ?></div>
    </fieldset>

    <fieldset><br></fieldset>

    <fieldset>
      <label for="feed_title"><?= __('admin.feed_title'); ?></label>
      <textarea type="feed_title" rows="3" name="feed_title"><?= $settings['feed_title']; ?></textarea>
    </fieldset>
    <fieldset>
      <label for="feed_desc"><?= __('admin.feed_desc'); ?></label>
      <textarea type="feed_desc" rows="3" name="feed_desc"><?= $settings['feed_desc']; ?></textarea>
    </fieldset>

    <fieldset>
      <label for="top_title"><?= __('admin.top_title'); ?></label>
      <textarea type="top_title" rows="3" name="top_title"><?= $settings['top_title']; ?></textarea>
    </fieldset>
    <fieldset>
      <label for="top_desc"><?= __('admin.top_desc'); ?></label>
      <textarea type="top_desc" rows="3" name="top_desc"><?= $settings['top_desc']; ?></textarea>
    </fieldset>

    <fieldset>
      <label for="all_title"><?= __('admin.all_title'); ?></label>
      <textarea type="all_title" rows="3" name="all_title"><?= $settings['all_title']; ?></textarea>
    </fieldset>
    <fieldset>
      <label for="all_desc"><?= __('admin.all_desc'); ?></label>
      <textarea type="all_desc" rows="3" name="all_desc"><?= $settings['all_desc']; ?></textarea>
    </fieldset>

    <fieldset><br></fieldset>


    <fieldset>
      <label for="img_path"><?= __('admin.img_path'); ?></label>
      <input value="<?= $settings['img_path']; ?>" type="text" required name="img_path">
      <div class="help"><?= __('admin.img_path_help'); ?></div>
    </fieldset>
    <fieldset>
      <label for="img_path"><?= __('admin.img_path_web'); ?></label>
      <input value="<?= $settings['img_path_web']; ?>" type="text" required name="img_path_web">
      <div class="help"><?= __('admin.img_path_web_help'); ?></div>
    </fieldset>

    <fieldset><br></fieldset>

    <fieldset>
      <label for="banner_title"><?= __('admin.banner_title'); ?></label>
      <input value="<?= $settings['banner_title']; ?>" type="text" required name="banner_title">
      <div class="help"><?= __('admin.banner_title_help'); ?></div>
    </fieldset>
    <fieldset>
      <label for="banner_desc"><?= __('admin.banner_desc'); ?></label>
      <textarea type="banner_desc" rows="3" name="banner_desc"><?= $settings['banner_desc']; ?></textarea>
    </fieldset>

    <fieldset><br></fieldset>

    <?= Html::sumbit(__('admin.edit')); ?>
  </form>
</div>
</main>
<?= insertTemplate('footer'); ?>