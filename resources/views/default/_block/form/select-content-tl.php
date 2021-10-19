<div class="boxline">
  <label class="block" for="post_content"><?= lang('for'); ?> TL</label>
  <select class="form-input" name="content_tl">
    <?php for ($i = 0; $i <= $uid['user_trust_level']; $i++) {  ?>
      <option <?php if ($data == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
    <?php } ?>
  </select>
</div>