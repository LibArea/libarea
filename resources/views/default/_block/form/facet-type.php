<fieldset>
  <label for="post_content"><?= __('app.type'); ?></label>
  <select name="facet_type">
    <?php foreach (App\Controllers\Facets\AllFacetController::types() as $value) : ?>
      <?php if (UserData::getRegType(config('trust-levels.tl_add_' . $value['type_code']))) : ?>
        <option <?php if (!empty($type)) : ?><?php if ($value['type_code'] == $type) : ?>selected<?php endif; ?><?php endif; ?> value="<?= $value['type_code']; ?>">
          <?= __('app.' . $value['type_lang']); ?>
        </option>
      <?php endif; ?>
    <?php endforeach; ?>
  </select>
</fieldset>