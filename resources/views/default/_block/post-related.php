<?php if (!empty($post_related)) { ?>
  <div class="mb20">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('related'); ?></h3>
    <?php $num = 0; ?>
    <?php foreach ($post_related as $related) { ?>
      <div class="mb5 flex">
        <?php $num++; ?>
        <div class="flex justify-center bg-gray-200 w21 mr5 br-rd-50 size-15">
          <span class="gray-light-2"><?= $num; ?></span>
        </div>
        <a href="<?= getUrlByName('post', ['id' => $related['post_id'], 'slug' => $related['post_slug']]); ?>">
          <?= $related['post_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>