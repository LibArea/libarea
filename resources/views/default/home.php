<?php if ($uid['user_id'] == 0) { ?>
  <div class="col-span-12 grid items-center grid-cols-12 mb5">
    <div class="col-span-12 bg-white border-box-1 br-rd5 p20 center">
      <h1 class="size-31 mt0"><?= Config::get('meta.bannertitle'); ?></h1>
      <div class="size-18 gray-light mb5"><?= Config::get('meta.bannerdesc'); ?>...</div>
    </div>
  </div>
<?php } ?>

<div class="sticky top0 col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>

<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd5 p15 mb15">
    <p class="m0 size-18"><?= lang($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'feed', 'url' => '/', 'content' => lang('feed'), 'icon' => 'bi bi-sort-down'],
      ['id' => 'all', 'url' => '/all', 'content' => lang('all'), 'auth' => 'yes', 'icon' => 'bi bi-app'],
      ['id' => 'top', 'url' => '/top', 'content' => lang('top'), 'icon' => 'bi bi-bar-chart'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>
  <?php if (Request::getUri() == '/' && $uid['user_id'] > 0 && empty($data['topics_user'])) { ?>
    <div class="center">
      <a class="bg-blue-100 bg-hover-green white-hover flex justify-center pt5 pr10 pb5 pl10 br-rd5 p15 mb15 blue size-14" href="/topics">
        <i class="bi bi-lightbulb middle mr5"></i>
        <?= lang('topic-subscription'); ?>
      </a>
    </div>
  <?php } ?>
  <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  <div class="mb15">
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  </div>
</main>
<aside class="col-span-3 mb-col-12 relative no-mob">
  <?php if ($uid['user_id'] == 0) { ?>
    <?= includeTemplate('/_block/login'); ?>
  <?php } ?>


  <?php if ($uid['user_id'] > 0 && !empty($data['topics_user'])) { ?>
    <div class="border-box-1 p15 mb15 br-rd5 bg-white size-14">
      <a class="right gray-light-2" title="<?= lang('topics'); ?>" href="<?= getUrlByName('topic.my'); ?>">
        <i class="bi bi-chevron-right middle"></i>
      </a>
      <div class="uppercase gray mt5 mb5">
        <?= lang('signed'); ?>
      </div>
      <?php 
      $n = 0;
      foreach ($data['topics_user'] as  $topic) { 
        $n++; 
        if($n > Config::get('topics.number_topics')) break; 
      ?>
        <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
          <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'w24 mr5'); ?>
          <span class="ml5"><?= $topic['topic_title']; ?></span>
        </a>
      <?php } ?>
      <?php if (count($data['topics_user']) > Config::get('topics.number_topics')) { ?>
        <a class="gray block mt5" title="<?= lang('topics'); ?>" href="<?= getUrlByName('topic.my'); ?>">
          <?= lang('see more'); ?> <i class="bi bi-chevron-double-right middle"></i>
        </a>
      <?php } ?>
    </div>
  <?php } ?>
  
  
   <?php if ($uid['user_id'] == 0) { ?>
    <div class="border-box-1 p15 mb15 br-rd5 bg-white size-14">
       <div class="uppercase gray mt5 mb5">
        <?= lang('topics'); ?>
      </div>
        <?php foreach (Config::get('topics-default') as $key => $topic) { ?>
           <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= $topic['url']; ?>">
            <img class="w24 mr5 border-box-1" src="<?= $topic['img']; ?>" alt="<?= $topic['name']; ?>">
            <span class="ml5"><?= $topic['name']; ?></span>
           </a>
        <?php } ?>
      </div>
    <?php } ?>  

  <div class="sticky top0 t-81">
    <?php if (!empty($data['latest_answers'])) { ?>
      <div class="last-comm border-box-1 p5 pr15 pb5 pl15 bg-white br-rd5">
        <?php foreach ($data['latest_answers'] as $answer) { ?>
          <div class="mt15 mr0 mb15 ml0">
            <div class="size-14 gray-light-2">
              <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18 br-rd-50'); ?>
              <?= $answer['answer_date']; ?>
            </div>
            <a class="black" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
              <?= $answer['answer_content']; ?>...
            </a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>

    <?= includeTemplate('/_block/footer-sidebar'); ?>
  </div>
</aside>