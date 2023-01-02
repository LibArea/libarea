<?php if (!empty($related_posts)) : ?>
  <div class="box bg-lightgray">
    <h4 class="uppercase-box"><?= __('app.related'); ?></h4>
    <ul class="list-none">
      <?php foreach ($related_posts as $related) : ?>
        <li class="flex items-center mt10">
          <?php if ($related['post_content_img']) : ?>
            <?= Img::image($related['post_content_img'], $related['value'], 'w60 mr20', 'post', 'cover'); ?>
          <?php endif; ?>
          <a href="<?= post_slug($related['id'], $related['post_slug']); ?>">
            <?= $related['value']; ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>