<?php if ($data['user']['id'] != 1) : ?>
  <div class="box bg-lightgray">
    <h4 class="uppercase-box"><?= __('app.created_by'); ?></h4>
    <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= url('profile', ['login' => $data['user']['login']]); ?>">
      <?= Html::image($data['user']['avatar'], $data['user']['login'], 'img-base mr5', 'avatar', 'max'); ?>
      <?= $data['user']['login']; ?>
    </a>
  </div>
<?php endif; ?>

<?php if (!empty($data['high_topics'])) : ?>
  <?= insert('/_block/facet/topic-block', ['data' => $data['high_topics'], 'lang' => 'upper']); ?>
<?php endif; ?>

<?php if (!empty($data['low_topics'])) : ?>
  <?= insert('/_block/facet/topic-block', ['data' => $data['low_topics'], 'lang' => 'subtopics']); ?>
<?php endif; ?>

<?php if (!empty($data['low_matching'])) : ?>
  <?= insert('/_block/facet/topic-block', ['data' => $data['low_matching'], 'lang' => 'related']); ?>
<?php endif; ?>