<?php foreach ($data as $fl) { ?>
  <div class="boxline">
    <label class="form-label" for="post_title"><?= $fl['title']; ?></label>
    <input class="form-input" 
            <?php if (!empty($fl['min'])) { ?>minlength="<?= $fl['min']; ?>"<?php } ?> 
            <?php if (!empty($fl['max'])) { ?>maxlength="<?= $fl['max']; ?>"<?php } ?>
            <?php if (!empty($fl['id'])) { ?>id="<?= $fl['id']; ?>"<?php } ?>
                type="<?= $fl['type']; ?>" value="<?= $fl['value']; ?>" name="<?= $fl['name']; ?>">
    <?php if (!empty($fl['help'])) { ?><div class="box_h gray"><?= $fl['help']; ?></div><?php } ?>
  </div>
<?php } ?>