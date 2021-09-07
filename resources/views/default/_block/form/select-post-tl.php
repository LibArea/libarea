<div class="boxline">
  <label class="form-label" for="post_content"><?= lang('For'); ?> TL</label>
  <select class="form-input" name="post_tl">
    <?php for ($i = 0; $i <= $uid['user_trust_level']; $i++) {  ?>
      <option <?php if ($data == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
    <?php } ?>
  </select>
</div>