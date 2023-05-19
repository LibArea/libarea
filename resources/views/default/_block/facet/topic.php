<?php if (!empty($data['high_topics'])) : ?>
  <?= insert('/_block/facet/topic-block', ['data' => $data['high_topics'], 'lang' => 'upper']); ?>
<?php endif; ?>

<?php if (!empty($data['low_topics'])) : ?>
  <?= insert('/_block/facet/topic-block', ['data' => $data['low_topics'], 'lang' => 'subtopics']); ?>
<?php endif; ?>

<?php if (!empty($data['low_matching'])) : ?>
  <?= insert('/_block/facet/topic-block', ['data' => $data['low_matching'], 'lang' => 'related']); ?>
<?php endif; ?>