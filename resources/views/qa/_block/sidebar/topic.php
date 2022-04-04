<?php if ($data['user']['id'] != 1) { ?>
  <div class="box-white bg-violet-50">
    <h3 class="uppercase-box"><?= Translate::get('created.by'); ?></h3>
    <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('profile', ['login' => $data['user']['login']]); ?>">
      <?= Html::image($data['user']['avatar'], $data['user']['login'], 'ava-base', 'avatar', 'max'); ?>
      <?= $data['user']['login']; ?>
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