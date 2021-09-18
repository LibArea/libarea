<?php if ($uid['user_id'] == 0) { ?>
  <div class="banner banner-bg justify-content-between hidden size-15 flex">
    <div class="wrap flex">
      <div class="pb5 text">
        <h1 class="size-31"><?= Agouti\Config::get(Agouti\Config::PARAM_BANNER_TITLE); ?></h1>
        <div class="size-21 pb15"><?= Agouti\Config::get(Agouti\Config::PARAM_BANNER_DESC); ?>...</div>
      </div>
      <div class="pb5">
        <?= returnBlock('banner-login'); ?>
      </div>
    </div>
  </div>
<?php } ?>

<div class="wrap">
  <main class="telo">
    <?php $pages = array(
      array('id' => 'feed', 'url' => '/', 'content' => lang('Feed')),
      array('id' => 'all', 'url' => '/all', 'content' => lang('All'), 'auth' => 'yes'),
      array('id' => 'top', 'url' => '/top', 'content' => lang('Top')),
    );
    echo returnBlock('tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>

    <?php if (Request::getUri() == '/' && $uid['user_id'] > 0 && empty($data['space_user'])) { ?>
      <div class="white-box pt5 pr15 pb5 pl15 big center gray">
        <i class="icon-lightbulb middle red"></i>
        <span class="middle"><?= lang('space-subscription'); ?>...</span>
      </div>
    <?php } ?>

    <?= returnBlock('/post', ['data' => $data, 'uid' => $uid]); ?>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  </main>
  <aside>
    <?php if ($uid['user_id'] > 0 && !empty($data['space_user'])) { ?>
      <div class="white-box pt5 pr15 pb5 pl15 size-13">
        <a class="right" title="<?= lang('Spaces'); ?>" href="/spaces">
          <i class="icon-right-open-big middle"></i>
        </a>
        <div class="uppercase mb5 mt5">
          <?= lang('Signed'); ?>
        </div>
        <?php foreach ($data['space_user'] as  $sig) { ?>
          <a class="flex relative pt5 pb5 hidden gray" href="<?= getUrlByName('space', ['slug' => $sig['space_slug']]); ?>" title="<?= $sig['space_name']; ?>">
            <?= spase_logo_img($sig['space_img'], 'small', $sig['space_name'], 'ava-24 mr5'); ?>
            <span class="ml5"><?= $sig['space_name']; ?></span>
            <?php if ($sig['space_user_id'] == $uid['user_id']) { ?>
              <sup class="red mr5 ml5">+</sup>
            <?php } ?>
          </a>
        <?php } ?>
        <?php if (count($data['space_user']) > 15) { ?>
          <a class="gray" title="<?= lang('Spaces'); ?>" href="/spaces">
            <?= lang('See more'); ?> <i class="icon-right-open-big middle"></i>
          </a>
        <?php } ?>
      </div>
    <?php } ?>

    <?php if (!empty($data['latest_answers'])) { ?>
      <div class="last-comm white-box sticky pt5 pr15 pb5 pl15">
        <?php foreach ($data['latest_answers'] as  $answer) { ?>
          <div class="mt15 mr0 mb15 ml0 pl15" style="border-left: 2px solid <?= $answer['space_color']; ?>;">
            <div class="size-13">
              <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava'); ?>
              <span class="ml5"></span>
              <?= $answer['answer_date']; ?>
            </div>
            <a class="gray" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
              <?= $answer['answer_content']; ?>...
            </a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </aside>
</div>