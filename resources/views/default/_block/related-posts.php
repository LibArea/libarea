<?php if (!empty($related_posts)) : ?>
  <div class="mt20 mb20">
    <h4 class="uppercase-box"><?= __('app.related'); ?></h4>
    <ul class="list-none">
      <?php foreach ($related_posts as $related) : ?>
        <li class="flex items-center mt10">
          <svg class="icon gray-600">
            <use xlink:href="/assets/svg/icons.svg#chevrons-right"></use>
          </svg>
          <a href="<?= post_slug($related['id'], $related['post_slug']); ?>">
            <?= $related['value']; ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>