<?= insertTemplate('/setting/nav', ['data' => $data, 'meta' => $meta]);
foreach ($data['settings'] as $val) {
  $settings[$val['val']] = $val['value'];
}

?>

<div>
  <form class="max-w-md" action="<?= url('admin.setting.edit', method: 'post'); ?>" method="post">
    <?= $container->csrf()->field(); ?>
    <h2><?= __('admin.advertising'); ?></h2>
    <fieldset>
      <label for="ads_home_post"><?= __('admin.ads_home_post'); ?></label>
      <textarea type="ads_home_post" rows="3" name="ads_home_post"><?= $settings['ads_home_post']; ?></textarea>
    </fieldset>
    <fieldset>
      <label for="ads_home_sidebar"><?= __('admin.ads_home_sidebar'); ?></label>
      <textarea type="ads_home_sidebar" rows="3" name="ads_home_sidebar"><?= $settings['ads_home_sidebar']; ?></textarea>
    </fieldset>
    <fieldset>
      <label for="ads_home_menu"><?= __('admin.ads_home_menu'); ?></label>
      <textarea type="ads_home_menu" rows="3" name="ads_home_menu"><?= $settings['ads_home_menu']; ?></textarea>
    </fieldset>

    <h2><?= __('admin.post'); ?></h2>
    <fieldset>
      <label for="ads_post_sidebar"><?= __('admin.ads_post_sidebar'); ?></label>
      <textarea type="ads_post_sidebar" rows="3" name="ads_post_sidebar"><?= $settings['ads_post_sidebar']; ?></textarea>
    </fieldset>
    <fieldset>
      <label for="ads_post_footer"><?= __('admin.ads_post_footer'); ?></label>
      <textarea type="ads_post_footer" rows="3" name="ads_post_footer"><?= $settings['ads_post_footer']; ?></textarea>
    </fieldset>
    <fieldset>
      <label for="ads_post_answer"><?= __('admin.ads_post_answer'); ?></label>
      <textarea type="ads_post_answer" rows="3" name="ads_post_answer"><?= $settings['ads_post_answer']; ?></textarea>
    </fieldset>

    <h2><?= __('admin.catalog'); ?></h2>
    <fieldset>
      <label for="ads_catalog_home"><?= __('admin.ads_catalog_home'); ?></label>
      <textarea type="ads_catalog_home" rows="3" name="ads_catalog_home"><?= $settings['ads_catalog_home']; ?></textarea>
    </fieldset>
    <fieldset>
      <label for="ads_catalog_sidebar"><?= __('admin.ads_catalog_sidebar'); ?></label>
      <textarea type="ads_catalog_sidebar" rows="3" name="ads_catalog_sidebar"><?= $settings['ads_catalog_sidebar']; ?></textarea>
    </fieldset>

    <?= Html::sumbit(__('admin.edit')); ?>
  </form>
</div>
</main>
<?= insertTemplate('footer'); ?>