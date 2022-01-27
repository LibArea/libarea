<fieldset>
  <label for="post_content"><?= Translate::get('for'); ?> TL</label>
  <select class="w-100 h30 bg-white br-box-gray" name="content_tl">
    <?php for ($i = 0; $i <= $user['trust_level']; $i++) {  ?>
      <option <?php if ($data == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
    <?php } ?>
  </select>
</fieldset>