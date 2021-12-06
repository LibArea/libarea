<?php foreach ($data as $value) { ?>
  <div class="mb20 max-w640">
    <label class="block"><?= $value['title']; ?></label>
    <input type="radio" name="<?= $value['name']; ?>" <?php if ($value['checked'] == 0) { ?>checked<?php } ?> value="0"> <?= Translate::get('no'); ?>
    <input type="radio" name="<?= $value['name']; ?>" <?php if ($value['checked'] == 1) { ?>checked<?php } ?> value="1"> <?= Translate::get('yes'); ?>
    <?php if (!empty($value['help'])) { ?><div class="size-14 gray-light-2"><?= $value['help']; ?></div><?php } ?>
  </div>
<?php } ?>