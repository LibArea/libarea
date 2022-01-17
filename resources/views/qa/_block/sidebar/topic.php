<?php if ($data['user']['id'] != 1) { ?>
  <div class="bg-white br-rd5 mb15 br-box-gray p15">
    <h3 class="uppercase mb5 mt0 font-light text-sm gray"><?= Translate::get('created by'); ?></h3>
    <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('profile', ['login' => $data['user']['login']]); ?>" title="<?= $data['user']['login']; ?>" >
      <?= user_avatar_img($data['user']['avatar'], 'max', $data['user']['login'], 'w30 h30 mr10'); ?>
      <?= $data['user']['login']; ?>
    </a>
  </div>
<?php } ?>

<?php if ($data['facet']['facet_is_web'] == 1) { ?>
  <div class="bg-white br-rd5 mb15 br-box-gray box-shadow-all p15">
    <a class="green-600" href="<?= getUrlByName('web.topic', ['slug' => $data['facet']['facet_slug']]); ?>">
      <i class="bi bi-link-45deg text-2xl middle"></i>
      <?= Translate::get('related sites'); ?>
    </a>
  </div>
<?php } ?>

<?php if (!empty($data['high_topics'])) { ?>
  <?= Tpl::import('/_block/sidebar/topic_block', ['data' => $data['high_topics'], 'lang' => 'upper']); ?>
<?php } ?>

<?php if (!empty($data['low_topics'])) { ?>
  <?= Tpl::import('/_block/sidebar/topic_block', ['data' => $data['low_topics'], 'lang' => 'subtopics']); ?>
<?php } ?>

<?php if (!empty($data['low_matching'])) { ?>
  <?= Tpl::import('/_block/sidebar/topic_block', ['data' => $data['low_matching'], 'lang' => 'related']); ?>
<?php } ?>