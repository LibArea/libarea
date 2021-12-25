<?php if ($data['user']['user_id'] != 1) { ?>
  <div class="bg-white br-rd5 mb15 br-box-gray p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('created by'); ?></h3>
    <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>" title="<?= $data['user']['user_login']; ?>" >
      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'w24 mr10'); ?>
      <?= $data['user']['user_login']; ?>
    </a>
  </div>
<?php } ?>

<?php if ($data['facet']['facet_is_web'] == 1) { ?>
  <div class="bg-white br-rd5 mb15 br-box-gray p15">
    <a class="green-600" href="<?= getUrlByName('web.topic', ['slug' => $data['facet']['facet_slug']]); ?>">
      <i class="bi bi-link-45deg size-21 middle"></i>
      <?= Translate::get('related sites'); ?>
    </a>
  </div>
<?php } ?>

<?php if (!empty($data['high_topics'])) { ?>
  <?= import('/_block/sidebar/topic_block', ['data' => $data['high_topics'], 'lang' => 'upper']); ?>
<?php } ?>

<?php if (!empty($data['low_topics'])) { ?>
  <?= import('/_block/sidebar/topic_block', ['data' => $data['low_topics'], 'lang' => 'subtopics']); ?>
<?php } ?>

<?php if (!empty($data['low_matching'])) { ?>
  <?= import('/_block/sidebar/topic_block', ['data' => $data['low_matching'], 'lang' => 'related']); ?>
<?php } ?>