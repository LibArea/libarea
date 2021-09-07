<div class="boxline">
  <label class="form-label" for="post_content"><?= lang('Space'); ?></label>
  <select class="form-input" name="space_id">
    <?php foreach ($spaces as $space) { ?>
      <option <?php if ($space_id == $space['space_id']) { ?> selected<?php } ?> value="<?= $space['space_id']; ?>">
        <?= $space['space_name']; ?>
      </option>
    <?php } ?>
  </select>
</div>