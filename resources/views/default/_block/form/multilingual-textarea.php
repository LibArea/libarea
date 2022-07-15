<fieldset class="tabs" id="<?= $name; ?>">
  <div class="tabs__nav">
    <?php foreach (config('general.languages') as $key => $lang) : ?>
      <button class="tabs__btn<?php if (config('general.lang') == $lang) { ?> tabs__btn_active<?php } ?>"><?= __('app.' . $lang . '_language'); ?></button>
    <?php endforeach; ?>
  </div>
  <div class="tabs__content">
    <?php foreach (config('general.languages') as $key => $lang) : ?>
      <div class="tabs__pane<?php if (config('general.lang') == $lang) { ?> tabs__pane_show<?php } ?>">
        <label for="<?= $name; ?>"><?= __('app.' . $name); ?> — <?= $lang; ?><sup class="red">*</sup></label>
        <textarea class="add max-w780 block" rows="6" name="lang[<?= $lang; ?>][<?= 'translation_' . $name; ?>]"><?= $arr[$key]['translation_' . $name] ?? ''; ?></textarea>
        <div class="help">> 3 <?= __('app.characters'); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</fieldset>