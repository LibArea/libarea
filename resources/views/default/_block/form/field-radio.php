<?php foreach ($data as $value) { ?>
  <div class="boxline">
    <label class="block"><?= $value['title']; ?></label>
    <input type="radio" name="<?= $value['name']; ?>" <?php if ($value['checked'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('no'); ?>
    <input type="radio" name="<?= $value['name']; ?>" <?php if ($value['checked'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('yes'); ?>
    <?php if (!empty($value['help'])) { ?><div class="size-14 gray-light-2"><?= $value['help']; ?></div><?php } ?>
  </div>
<?php } ?>