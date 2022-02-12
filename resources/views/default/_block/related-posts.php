<?php if (!empty($related_posts)) { ?>
  <h3 class="uppercase-box"><?= Translate::get('related'); ?></h3>
  <ol>
  <?php foreach ($related_posts as $related) { ?>
    <li class="gray-400">
      <a href="<?= getUrlByName('post', ['id' => $related['id'], 'slug' => $related['post_slug']]); ?>">
        <?= $related['value']; ?>
      </a>
    </li>
  <?php } ?>
  </ol>
<?php } ?>