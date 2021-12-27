<div class="mb20 max-w640">
  <label class="block" for="post_content"><?= Translate::get('type'); ?></label>
  <select class="h40" name="facet_type">
    <?php foreach (Config::get('facets.facet_type') as $value) { ?>
      <option <?php if ($value['value'] == $type) { ?>selected<?php } ?> value="<?= $value['value']; ?>">
        <?= $value['title']; ?>
      </option>
    <?php } ?>
  </select>
</div>