<?php if ($uid['user_id'] == 0) { ?>
  <div class="col-span-12 grid items-center grid-cols-12 mb5">
    <div class="col-span-12 bg-white border-box-1 br-rd-5 p20">
      <div class="center">
        <h1 class="size-31"><?= Agouti\Config::get(Agouti\Config::PARAM_BANNER_TITLE); ?></h1>
        <div class="size-18 gray-light pb15"><?= Agouti\Config::get(Agouti\Config::PARAM_BANNER_DESC); ?>...</div>
      </div>
    </div>
  </div>
<?php } ?>

<div class="sticky top0 col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>

<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 p15 mb15">
    <p class="m0 size-18"><?= lang($data['sheet']); ?></p>
    <?php $pages = array(
      array('id' => 'feed', 'url' => '/', 'content' => lang('feed'), 'icon' => 'icon-air'),
      array('id' => 'all', 'url' => '/all', 'content' => lang('all'), 'auth' => 'yes', 'icon' => 'icon-calendar'),
      array('id' => 'top', 'url' => '/top', 'content' => lang('top'), 'icon' => 'icon-chart-bar'),
    );
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>
  <?php if (Request::getUri() == '/' && $uid['user_id'] > 0 && empty($data['space_user'])) { ?>
    <div class="border-box-1 p15 big center gray">
      <i class="icon-lightbulb middle red"></i>
      <span class="middle"><?= lang('space-subscription'); ?>...</span>
    </div>
  <?php } ?>

  <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  <div class="mb15">
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  </div>
</main>
<aside class="col-span-3 mb-col-12 no-mob">
  <?php if ($uid['user_id'] == 0) { ?>
    <?= includeTemplate('/_block/login'); ?>
  <?php } ?>

  <?php if ($uid['user_id'] > 0 && !empty($data['space_user'])) { ?>
    <div class="border-box-1 p15 mb15 br-rd-3 bg-white size-14">
      <a class="right gray-light" title="<?= lang('spaces'); ?>" href="/spaces">
        <i class="icon-right-open-big middle"></i>
      </a>
      <div class="uppercase mt0 mb5">
        <?= lang('signed'); ?>
      </div>
      <?php foreach ($data['space_user'] as  $sig) { ?>
        <a class="flex relative pt5 pb5 hidden gray" href="<?= getUrlByName('space', ['slug' => $sig['space_slug']]); ?>" title="<?= $sig['space_name']; ?>">
          <?= spase_logo_img($sig['space_img'], 'small', $sig['space_name'], 'w24 mr5'); ?>
          <span class="ml5"><?= $sig['space_name']; ?></span>
          <?php if ($sig['space_user_id'] == $uid['user_id']) { ?>
            <sup class="red mr5 ml5">+</sup>
          <?php } ?>
        </a>
      <?php } ?>
      <?php if (count($data['space_user']) > 15) { ?>
        <a class="gray" title="<?= lang('spaces'); ?>" href="/spaces">
          <?= lang('see more'); ?> <i class="icon-right-open-big middle"></i>
        </a>
      <?php } ?>
    </div>
  <?php } ?>

  <div class="sticky top0 t-81">
    <?php if (!empty($data['latest_answers'])) { ?>
      <div class="last-comm border-box-1 p15 bg-white">
        <?php foreach ($data['latest_answers'] as  $answer) { ?>
          <div class="mt15 mr0 mb15 ml0 pl15" style="border-left: 2px solid <?= $answer['space_color']; ?>;">
            <div class="size-14 gray-light">
              <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18'); ?>
              <span class="ml5"></span>
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