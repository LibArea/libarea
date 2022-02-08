<fieldset>
  <label for="post_content"><?= Translate::get('type'); ?></label>
  <select class="h40" name="facet_type">
    <?php foreach (App\Controllers\Facets\AllFacetController::types() as $value) { ?>
      <option <?php if ($value['type_code'] == $type) { ?>selected<?php } ?> value="<?= $value['type_code']; ?>">
        <?= Translate::get($value['type_lang']); ?>
      </option>
    <?php } ?>
   </select>
</fieldset>