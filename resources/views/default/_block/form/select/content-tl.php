<fieldset>
  <label for="post_content"><?= __('for'); ?> TL</label>
  <select name="content_tl">
    <?php for ($i = 0; $i <= $user['trust_level']; $i++) {  
      if ($i == UserData::USER_FIFTH_LEVEL + 1) { break; }
    ?>
      <option <?php if ($data == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
    <?php } ?>
  </select>
  <div class="help"><?= __('view.post.tl'); ?>...</div>
</fieldset>