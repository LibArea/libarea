<?php include TEMPLATE_DIR . '/header.php'; ?>

<?php if ($uid['id'] == 0) { ?>
  <div class="banner size-15">
    <div class="pt15 pb5">
      <h1 class="red size-21"><?= Lori\Config::get(Lori\Config::PARAM_BANNER_TITLE); ?></h1>
      <div class="pb5"><?= Lori\Config::get(Lori\Config::PARAM_BANNER_DESC); ?>...</div>
    </div>
  </div>
<?php } ?>

<div class="wrap">
  <main class="telo">
    <ul class="nav-tabs mt0 mb15">
      <?php if ($data['sheet'] == 'feed') { ?>
        <li class="active">
          <span><?= lang('Feed'); ?></span>
        </li>
        <?php if ($uid['id']) { ?>
          <li>
            <a href="/all">
              <span><?= lang('All'); ?></span>
            </a>
          </li>
        <?php } ?>
        <li>
          <a href="/top">
            <span>Top</span>
          </a>
        </li>
      <?php } elseif ($data['sheet'] == 'all') { ?>
        <li>
          <a href="/">
            <span><?= lang('Feed'); ?></span>
          </a>
        </li>
        <?php if ($uid['id']) { ?>
          <li class="active">
            <span><?= lang('All'); ?></span>
          </li>
        <?php } ?>
        <li>
          <a href="/top">
            <span>Top</span>
          </a>
        </li>
      <?php } else { ?>
        <li>
          <a href="/">
            <span><?= lang('Feed'); ?></span>
          </a>
        </li>
        <?php if ($uid['id']) { ?>
          <li>
            <a href="/all">
              <span><?= lang('All'); ?></span>
            </a>
          </li>
        <?php } ?>
        <li class="active">
          <span>Top</span>
        </li>
      <?php } ?>
    </ul>

    <?php if ($uid['uri'] == '/' && $uid['id'] > 0 && empty($data['space_user'])) { ?>
      <div class="white-box">
        <div class="pt5 pr15 pb5 pl15 big center gray">
          <i class="light-icon-bulb middle red"></i>
          <span class="middle"><?= lang('space-subscription'); ?>...</span>
        </div>
      </div>
    <?php } ?>

    <?php include TEMPLATE_DIR . '/_block/post.php'; ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>

  </main>
  <aside>
    <?php if ($uid['id']) { ?>
      <?php if (!empty($data['space_user'])) { ?>
        <div class="white-box">
          <div class="pt5 pr15 pb5 pl15">
            <a class="right" title="<?= lang('Spaces'); ?>" href="/spaces"><i class="light-icon-chevron-right"></i></a>
            <div class="bar-title size-13">
              <?= lang('Signed'); ?>
            </div>
            <?php foreach ($data['space_user'] as  $sig) { ?>
              <a class="bar-space-telo gray" href="/s/<?= $sig['space_slug']; ?>" title="<?= $sig['space_name']; ?>">
                <?= spase_logo_img($sig['space_img'], 'small', $sig['space_name'], 'ava-24'); ?>
                <span class="mr5 ml5 size-13"><?= $sig['space_name']; ?></span>
                <?php if ($sig['space_user_id'] == $uid['id']) { ?>
                  <sup class="red mr5 ml5">+</sup>
                <?php } ?>
              </a>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
    <?php } ?>

    <?php if (!empty($data['latest_answers'])) { ?>
      <div class="last-comm white-box sticky">
        <div class="pt5 pr15 pb5 pl15">
          <?php foreach ($data['latest_answers'] as  $answer) { ?>
            <div class="mt15 mr0 mb15 ml0 pl15" style="border-left: 2px solid <?= $answer['space_color']; ?>;">
              <div class="size-13">
                <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'ava'); ?>
                <span class="mr5 ml5"></span>
                <?= $answer['answer_date']; ?>
              </div>
              <a class="gray" href="/post/<?= $answer['post_id']; ?>/<?= $answer['post_slug']; ?>#answer_<?= $answer['answer_id']; ?>">
                <?= $answer['answer_content']; ?>...
              </a>
            </div>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
  </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>