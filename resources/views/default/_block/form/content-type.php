<fieldset>
  <div class="form-label input-label"><label><?= __('app.type'); ?></label></div>
  <div class="form-element">
  <select name="content_type">
    <?php foreach (config('publication', 'allowed_types') as $value) : ?>
      <?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_' . $value['type']))) : ?>
        <option <?php if (!empty($type)) : ?><?php if ($value['type'] == $type) : ?>selected<?php endif; ?><?php endif; ?> value="<?= $value['type']; ?>">
          <?= __($value['title']); ?>
        </option>
      <?php endif; ?>
    <?php endforeach; ?>
  </select>
  </div>
</fieldset>