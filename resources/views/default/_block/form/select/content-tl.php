<div class="mb20 max-w640">
  <label class="block" for="post_content"><?= Translate::get('tl content'); ?></label>
  <select class="w-100 h30 bg-white br-box-gray" name="content_tl">
    <?php for ($i = 0; $i <= $uid['user_trust_level']; $i++) {  ?>
      <option <?php if ($data == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
    <?php } ?>
  </select>
  <div class="gray size-14"><?= Translate::get('tl info content'); ?>...</div>
</div>