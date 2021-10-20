<?php foreach ($data as $fl) { ?>
  <div class="mb20 max-w640">
    <label class="block" for="post_title"><?= $fl['title']; ?>
      <?php if (!empty($fl['red'])) { ?><sup class="red">*</sup><?php } ?>
    </label>
    <input class="w-100 h30" <?php if (!empty($fl['min'])) { ?>minlength="<?= $fl['min']; ?>" <?php } ?> <?php if (!empty($fl['max'])) { ?>maxlength="<?= $fl['max']; ?>" <?php } ?> <?php if (!empty($fl['id'])) { ?>id="<?= $fl['id']; ?>" <?php } ?> type="<?= $fl['type']; ?>" value="<?= $fl['value']; ?>" name="<?= $fl['name']; ?>">
    <?php if (!empty($fl['help'])) { ?><div class="size-14 gray-light-2"><?= $fl['help']; ?></div><?php } ?>
  </div>
<?php } ?>