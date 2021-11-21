<?php if ($data['user']['user_id'] != 1) { ?>
  <div class="bg-white br-rd5 mb15 br-box-gray p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('created by'); ?></h3>
    <a title="<?= $data['user']['user_login']; ?>" class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>">
      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'w24 mr10'); ?>
      <?= $data['user']['user_login']; ?>
    </a>
  </div>
<?php } ?>

<?php if (!empty($data['high_topics'])) { ?>
  <div class="bg-white br-rd5 mb15 br-box-gray p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('upper'); ?></h3>
    <?php foreach ($data['high_topics'] as $sub) { ?>
      <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>">
        <?= facet_logo_img($sub['facet_img'], 'max', $sub['facet_title'], 'w24 mr10 br-box-gray'); ?>
        <?= $sub['facet_title']; ?>
      </a>
    <?php } ?>
  </div>
<?php } ?>

<?php if (!empty($data['low_topics'])) { ?>
  <div class="bg-white br-rd5 mb15 br-box-gray p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('subtopics'); ?></h3>
    <?php foreach ($data['low_topics'] as $sub) { ?>
      <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>">
        <?= facet_logo_img($sub['facet_img'], 'max', $sub['facet_title'], 'w24 mr10 br-box-gray'); ?>
        <?= $sub['facet_title']; ?>
      </a>
    <?php } ?>
  </div>
<?php } ?>

<?php if (!empty($data['facet_related'])) { ?>
  <div class="bg-white br-rd5 mb15 br-box-gray p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('related'); ?></h3>
    <?php foreach ($data['facet_related'] as $related) { ?>
      <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $related['facet_slug']]); ?>">
        <?= facet_logo_img($related['facet_img'], 'max', $related['facet_title'], 'w24 mr10 br-box-gray'); ?>
        <?= $related['facet_title']; ?>
      </a>
    <?php } ?>
  </div>
<?php } ?>