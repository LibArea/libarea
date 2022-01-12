<?php if (!empty($related_posts)) { ?>
  <div class="mb15">
    <h3 class="uppercase mb5 mt0 font-light text-sm gray"><?= Translate::get('related'); ?></h3>
    <?php $num = 0; ?>
    <?php foreach ($related_posts as $related) { ?>
      <div class="mb10 flex">
        <?php if ($number == 'yes') { ?>
          <?php $num++; ?>
          <div class="flex justify-center bg-sky-50 w21 mr5 br-rd-50">
            <span class="gray-400"><?= $num; ?></span>
          </div>
        <?php } ?>
        <a href="<?= getUrlByName('post', ['id' => $related['id'], 'slug' => $related['post_slug']]); ?>">
          <?= $related['value']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>