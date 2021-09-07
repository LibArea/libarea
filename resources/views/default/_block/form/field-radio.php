<?php foreach ($data as $value) { ?>
  <div class="boxline">
    <label class="form-label"><?= $value['title']; ?></label>
    <input type="radio" name="<?= $value['name']; ?>" <?php if ($value['checked'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
    <input type="radio" name="<?= $value['name']; ?>" <?php if ($value['checked'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
    <?php if (!empty($value['help'])) { ?><div class="box_h gray"><?= $value['help']; ?></div><?php } ?>
  </div>
<?php } ?>