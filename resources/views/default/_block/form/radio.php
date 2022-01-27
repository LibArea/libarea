<?php foreach ($data as $value) {
  $checked  = $value['checked'] ?? 0;
  $help     = $value['help'] ?? null;
?>
  <fieldset>
    <label><?= $value['title']; ?></label>
    <input type="radio" name="<?= $value['name']; ?>" <?php if ($checked == 0) { ?>checked<?php } ?> value="0">
    <span class="checkmark"></span>      
    <?= Translate::get('no'); ?>

    <input type="radio" name="<?= $value['name']; ?>" <?php if ($checked == 1) { ?>checked<?php } ?> value="1">
    <span class="checkmark"></span>      
      <?= Translate::get('yes'); ?>
    <?php if ($help) { ?><div class="mt5 text-sm gray-400"><?= $help; ?></div><?php } ?>
  </fieldset>
<?php } ?>