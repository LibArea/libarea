<?php foreach ($data as $value) {
  $checked  = $value['checked'] ?? 0;
  $help     = $value['help'] ?? null;
?>
  <div class="mb20 max-w640">
    <label class="block mb5"><?= $value['title']; ?></label>
    <label class="container-radio">
      <input type="radio" name="<?= $value['name']; ?>" <?php if ($checked == 0) { ?>checked<?php } ?> value="0">
      <span class="checkmark"></span>      
      <?= Translate::get('no'); ?>
    </label> 
    <label class="container-radio"> 
      <input type="radio" name="<?= $value['name']; ?>" <?php if ($checked == 1) { ?>checked<?php } ?> value="1">
      <span class="checkmark"></span>      
      <?= Translate::get('yes'); ?>
    </label>
    <?php if ($help) { ?><div class="mt5 text-sm gray-400"><?= $help; ?></div><?php } ?>
  </div>
<?php } ?>