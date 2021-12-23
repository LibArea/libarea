<?php if ($uid['user_trust_level'] == 5) { ?>
  <div class="mt15 mb20">
    <label class="block mb5"><?= Translate::get('topic'); ?> / <?= Translate::get('blog'); ?>?</label>
    <?php if (!empty($data['facet_type'])) { ?>
      <input type="radio" name="facet_type" <?php if ($data['facet_type'] == 'topic') { ?>checked<?php } ?> value="topic">
      <?= Translate::get('topic'); ?>
      <input type="radio" name="facet_type" <?php if ($data['facet_type'] == 'blog') { ?>checked<?php } ?> value="blog">
      <?= Translate::get('blog'); ?>
      <input type="radio" name="facet_type" <?php if ($data['facet_type'] == 'section') { ?>checked<?php } ?> value="section">
      <?= Translate::get('section'); ?>
    <?php } else { ?>
      <input type="radio" name="facet_type" checked value="topic"> <?= Translate::get('topic'); ?>
      <input type="radio" name="facet_type" value="blog"> <?= Translate::get('blog'); ?>
      <input type="radio" name="facet_type" value="section"> <?= Translate::get('section'); ?>
    <?php } ?>
    <?php if (!empty($fl['help'])) { ?><div class="size-14 gray-light-2"><?= $fl['help']; ?></div><?php } ?>
  </div>
<?php } ?>