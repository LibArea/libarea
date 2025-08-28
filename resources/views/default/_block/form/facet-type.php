<fieldset>
  <label for="post_content"><?= __('app.type'); ?></label>
  <select name="facet_type">
    <?php foreach (App\Controllers\Facet\FacetController::types() as $value) : ?>
	  <?php // из базы "Категория" не стоит удалять, запретим тут для UX
	  if ($value['type_code'] != 'category') : ?>
        <?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_' . $value['type_code']))) : ?>
          <option <?php if (!empty($type)) : ?><?php if ($value['type_code'] == $type) : ?>selected<?php endif; ?><?php endif; ?> value="<?= $value['type_code']; ?>">
            <?= __('app.' . $value['type_lang']); ?>
          </option>
        <?php endif; ?>
	  <?php endif; ?>
    <?php endforeach; ?>
  </select>
</fieldset>