<?php if (!empty($related_posts)) { ?>
  <div class="mb15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('related'); ?></h3>
    <?php $num = 0; ?>
    <?php foreach ($related_posts as $related) { ?>
      <div class="mb5 flex">
        <?php $num++; ?>
        <div class="flex justify-center bg-gray-200 w21 mr5 br-rd-50 size-15">
          <span class="gray-light-2"><?= $num; ?></span>
        </div>
        <a href="<?= getUrlByName('post', ['id' => $related['value'], 'slug' => $related['post_slug']]); ?>">
          <?= $related['post_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>