<?php if ($data['user']['user_id'] != 1) { ?>
  <div class="bg-white br-rd5 border-box-1 p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('created by'); ?></h3>
    <a title="<?= $data['user']['user_login']; ?>" class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>">
      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'w24 mr10'); ?>
      <?= $data['user']['user_login']; ?>
    </a>
  </div>
<?php } ?>

<?php if (!empty($data['main_topic'])) { ?>
  <div class="bg-white br-rd5 border-box-1 p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('root'); ?></h3>
    <div class="related-box">
      <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $data['main_topic']['topic_slug']]); ?>">
        <?= topic_logo_img($data['main_topic']['topic_img'], 'max', $data['main_topic']['topic_title'], 'w24 mr10 border-box-1'); ?>
        <?= $data['main_topic']['topic_title']; ?>
      </a>
    </div>
  </div>
<?php } ?>

<?php if (!empty($data['subtopics'])) { ?>
  <div class="bg-white br-rd5 border-box-1 p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('subtopics'); ?></h3>
    <?php foreach ($data['subtopics'] as $sub) { ?>
      <div class="related-box">
        <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $sub['topic_slug']]); ?>">
          <?= topic_logo_img($sub['topic_img'], 'max', $sub['topic_title'], 'w24 mr10 border-box-1'); ?>
          <?= $sub['topic_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<?php if (!empty($data['topic_related'])) { ?>
  <div class="bg-white br-rd5 border-box-1 p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('related'); ?></h3>
    <?php foreach ($data['topic_related'] as $related) { ?>
      <div class="related-box">
        <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $related['topic_slug']]); ?>">
          <?= topic_logo_img($related['topic_img'], 'max', $related['topic_title'], 'w24 mr10 border-box-1'); ?>
          <?= $related['topic_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>