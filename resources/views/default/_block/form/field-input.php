<?php foreach ($data as $fl) { ?>
  <fieldset>
    <label for="post_title"><?= $fl['title']; ?>
      <?php if (isset($fl['red'])) { ?><sup class="red-500">*</sup><?php } ?>
    </label>
    <input  
    <?php if (isset($fl['min'])) { ?>minlength="<?= $fl['min']; ?>" <?php } ?> 
    <?php if (isset($fl['max'])) { ?>maxlength="<?= $fl['max']; ?>" <?php } ?> 
    <?php if (isset($fl['id'])) { ?>id="<?= $fl['id']; ?>" <?php } ?> 
    type="<?= $fl['type']; ?>" 
    <?php if (isset($fl['required'])) { ?> required <?php } ?> 
    <?php if (isset($fl['value'])) { ?>value="<?= $fl['value']; ?>" <?php } ?> 
    name="<?= $fl['name']; ?>">
    <?php if (isset($fl['help'])) { ?><div class="text-sm gray-400"><?= $fl['help']; ?></div><?php } ?>
  </fieldset>
<?php } ?>