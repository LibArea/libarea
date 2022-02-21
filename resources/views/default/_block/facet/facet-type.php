<fieldset>
  <label for="post_content"><?= Translate::get('type'); ?></label>
  <select name="facet_type">
    <?php foreach (App\Controllers\Facets\AllFacetController::types() as $value) { ?>
        <?php if ($tl >= Config::get('trust-levels.tl_add_' . $value['type_code'])) { ?>
            <option <?php if (!empty($type)) { ?><?php if ($value['type_code'] == $type) { ?>selected<?php } ?><?php } ?> value="<?= $value['type_code']; ?>">
                <?= Translate::get($value['type_lang']); ?>
            </option>
      <?php } ?>
    <?php } ?>
   </select>
</fieldset>