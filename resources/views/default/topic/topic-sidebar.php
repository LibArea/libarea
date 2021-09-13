<?php if (!empty($data['main_topic'])) { ?>
  <div class="white-box p15">
    <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Root'); ?></h3>
    <div class="related-box">
      <a class="tags gray size-13" href="/topic/<?= $data['main_topic']['topic_slug']; ?>">
        <?= $data['main_topic']['topic_title']; ?>
      </a>
    </div>
  </div>
<?php } ?>

<?php if (!empty($data['subtopics'])) { ?>
  <div class="white-box p15">
    <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Subtopics'); ?></h3>
    <?php foreach ($data['subtopics'] as $sub) { ?>
      <div class="related-box">
        <a class="tags gray size-13" href="/topic/<?= $sub['topic_slug']; ?>">
          <?= $sub['topic_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<?php if (!empty($data['topic_related'])) { ?>
  <div class="white-box p15">
    <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Related'); ?></h3>
    <?php foreach ($data['topic_related'] as $related) { ?>
      <div class="related-box">
        <a class="tags gray size-13" href="/topic/<?= $related['topic_slug']; ?>">
          <?= $related['topic_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>