<?php if ($data['user']['user_id'] != 1) { ?>
  <div class="bg-white br-rd-5 border-box-1 p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('created by'); ?></h3>
    <a title="<?= $data['user']['user_login']; ?>" class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>">
      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'w24 mr5'); ?>
      <?= $data['user']['user_login']; ?>
    </a>
  </div>
<?php } ?>

<?php if (!empty($data['main_topic'])) { ?>
  <div class="bg-white br-rd-5 border-box-1 p15">
    <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('root'); ?></h3>
    <div class="related-box">
      <a class="bg-blue-100 bg-hover-300 white-hover flex justify-center pt5 pr10 pb5 pl10 mb5 br-rd-20 blue inline size-14" href="<?= getUrlByName('topic', ['slug' => $data['main_topic']['topic_slug']]); ?>">
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
        <a class="bg-blue-100 bg-hover-300 white-hover flex justify-center pt5 pr10 pb5 pl10 mb5 br-rd-20 blue inline size-14" href="<?= getUrlByName('topic', ['slug' => $sub['topic_slug']]); ?>">
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
        <a class="bg-blue-100 bg-hover-300 white-hover flex justify-center pt5 pr10 pb5 pl10 mb5 block br-rd-20 blue inline size-14" href="<?= getUrlByName('topic', ['slug' => $related['topic_slug']]); ?>">
          <?= $related['topic_title']; ?>
        </a>
      </div>
    <?php } ?>
  </div>
<?php } ?>