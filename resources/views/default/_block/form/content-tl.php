<fieldset>
  <label for="post_content"><?= __('app.for'); ?> TL</label>
  <select name="content_tl">
    <?php for ($i = 0; $i <= $container->user()->tl(); $i++) {
      if ($i == 5) {
        break;
      }
    ?>
      <option <?php if ($data == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
    <?php } ?>
  </select>
  <div class="help"><?= __('app.view_post_tl'); ?>...</div>
</fieldset>