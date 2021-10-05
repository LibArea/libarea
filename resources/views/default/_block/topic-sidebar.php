<?php if (!empty($data['main_topic'])) { ?>
  <div class="bg-white br-rd-5 border-box-1 p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('root'); ?></h3>
    <div class="related-box">
      <a class="tags inline gray size-14" href="<?= getUrlByName('topic', ['slug' => $data['main_topic']['topic_slug']]); ?>">
        <?= $data['main_topic']['topic_title']; ?>
      </a>
    </div>
  </div>
<?php } ?>

<?php if (!empty($data['subtopics'])) { ?>
  <div class="bg-white br-rd-5 border-box-1 p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('subtopics'); ?></h3>
    <?php foreach ($data['subtopics'] as $sub) { ?>
      <div class="related-box">
        <a class="tags inline gray size-14" href="<?= getUrlByName('topic', ['slug' => $sub['topic_slug']]); ?>">
          <?= $sub['topic_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<?php if (!empty($data['topic_related'])) { ?>
  <div class="bg-white br-rd-5 border-box-1 p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('related'); ?></h3>
    <?php foreach ($data['topic_related'] as $related) { ?>
      <div class="related-box">
        <a class="tags inline gray size-14" href="<?= getUrlByName('topic', ['slug' => $related['topic_slug']]); ?>">
          <?= $related['topic_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>