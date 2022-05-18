<fieldset>
  <label for="facet_title"><?= __('app.title'); ?> <strong class="red">*</strong></label>
  <input id="facet_title" name="facet_title" required="" type="text" value="">
  <div class="text-sm gray-600">3 - 64 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="facet_short_description"><?= __('app.short_description'); ?> <strong class="red">*</strong></label>
  <input id="facet_short_description" name="facet_short_description" required="" type="text" value="">
  <div class="text-sm gray-600">9 - 120 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="facet_seo_title"><?= __('app.title'); ?> (SEO) <strong class="red">*</strong></label>
  <input id="facet_seo_title" name="facet_seo_title" required="" type="text" value="">
  <div class="text-sm gray-600">4 - 32 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="facet_slug"><?= __('app.slug'); ?> <strong class="red">*</strong></label>
  <input id="facet_slug" name="facet_slug" required="" type="text" value="">
  <div class="text-sm gray-600">4 - 32 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="facet_description"><?= __('app.meta_description'); ?> <strong class="red">*</strong></label>
  <textarea id="facet_description" name="facet_description" required=""></textarea>
  <div class="text-sm gray-600">~ 34 <?= __('app.characters'); ?></div>
</fieldset>

<?= Html::sumbit(__('app.add')); ?>