<?php foreach ($data as $fl) { ?>
  <fieldset>
    <label for="post_title"><?= $fl['title']; ?>
      <?php if (!empty($fl['red'])) { ?><sup class="red-500">*</sup><?php } ?>
    </label>
    <input  
    <?php if (!empty($fl['min'])) { ?>minlength="<?= $fl['min']; ?>" <?php } ?> 
    <?php if (!empty($fl['max'])) { ?>maxlength="<?= $fl['max']; ?>" <?php } ?> 
    <?php if (!empty($fl['id'])) { ?>id="<?= $fl['id']; ?>" <?php } ?> 
    type="<?= $fl['type']; ?>" 
    <?php if (!empty($fl['required'])) { ?> required <?php } ?> 
    <?php if (!empty($fl['value'])) { ?>value="<?= $fl['value']; ?>" <?php } ?> 
    name="<?= $fl['name']; ?>">
    <?php if (!empty($fl['help'])) { ?><div class="text-sm gray-400"><?= $fl['help']; ?></div><?php } ?>
  </fieldset>
<?php } ?>