<div class="mb20">
  <label class="block" for="about"><?= $title; ?></label>
  <textarea type="<?= $type; ?>" rows="4" <?php if (!empty($min)) { ?>minlength="<?= $min; ?>" <?php } ?> <?php if (!empty($max)) { ?>maxlength="<?= $max; ?>" <?php } ?> name="<?= $name; ?>"><?php if (!empty($content)) { ?><?= $content; ?><?php } ?></textarea>
  <?php if (!empty($help)) { ?><div class="size-14 gray-400"><?= $help; ?></div><?php } ?>
</div>