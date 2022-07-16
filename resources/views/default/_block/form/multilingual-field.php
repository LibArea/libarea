<fieldset class="tabs" id="<?= $name; ?>">
  <div class="tabs__nav">
    <?php foreach ($arr as $lang) : ?>
      <button class="tabs__btn"><?= $lang['translation_code']; ?></button>
    <?php endforeach; ?>
  </div>
  <div class="tabs__content">
    <?php foreach ($arr as $key => $lang) : ?>
      <div class="tabs__pane">
        <label for="<?= $name; ?>"><?= __('app.' . $name); ?> <span class="help">(<?= __('app.' . $lang['translation_code'] . '_language'); ?>)</span> <sup class="red">*</sup></label>
        
        <?php if ($type == 'input') : ?>
          <input minlength="3" type="text" name="lang[<?= $lang; ?>][<?= 'translation_' . $name; ?>]" value="<?= $arr[$key]['translation_' . $name] ?? ''; ?>">
        <?php else : ?>
          <textarea class="add max-w780 block" rows="6" name="lang[<?= $lang; ?>][<?= 'translation_' . $name; ?>]"><?= $arr[$key]['translation_' . $name] ?? ''; ?></textarea>
        <?php endif; ?>
        
        <div class="help">> 3 <?= __('app.characters'); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</fieldset>