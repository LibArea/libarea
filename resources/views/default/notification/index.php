<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12 bg-white pt5 pr15 pb5 pl15">
  <a class="right size-14 button block br-rd-5 white mt5 pb15" href="/notifications/delete">
    <?= lang('i read'); ?>
  </a>
  <?= breadcrumb('/', lang('home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('profile'), lang('notifications')); ?>

  <?php if (!empty($data['notifications'])) { ?>
    <?php foreach ($data['notifications'] as  $notif) { ?>

      <div class="border-bottom p5<?php if ($notif['notification_read_flag'] == 0) { ?> bg-gray-200<?php } ?>">
        <?php if ($notif['notification_action_type'] == 1) { ?>
          <i class="icon-mail middle"></i>
          <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>"><?= $notif['user_login']; ?></a>
          <?= lang('wrote to you'); ?>
          <a href="/notifications/read/<?= $notif['notification_id']; ?>"><?= lang('message'); ?></a>
        <?php } ?>

        <?php if ($notif['notification_action_type'] == 2) { ?>
          <?= lang('wrote a post'); ?>
        <?php } ?>

        <?php if ($notif['notification_action_type'] == 3) { ?>
          <i class="icon-book-open middle"></i>
          <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>">@<?= $notif['user_login']; ?></a>
          <a class="ntf2 lowercase" href="/notifications/read/<?= $notif['notification_id']; ?>">
            <?= lang('replied to post'); ?>
          </a>
        <?php } ?>

        <?php if ($notif['notification_action_type'] == 10 || $notif['notification_action_type'] == 11 || $notif['notification_action_type'] == 12) { ?>
          <i class="icon-user-o middle"></i>
          <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>">@<?= $notif['user_login']; ?></a>
          <?= lang('appealed to you'); ?>
          <a class="ntf2 lowercase" href="/notifications/read/<?= $notif['notification_id']; ?>">
            <?php if ($notif['notification_action_type'] == 10) { ?>
              <?= lang('in post'); ?>
            <?php } elseif ($notif['notification_action_type'] == 11) { ?>
              <?= lang('in answer'); ?>
            <?php } else { ?>
              <?= lang('in the comment'); ?>
            <?php } ?>
          </a>
        <?php } ?>
        <?php if ($notif['notification_action_type'] == 20) { ?>
          <i class="icon-warning-empty middle red"></i>
          <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>"><?= $notif['user_login']; ?></a>
          <?= lang('complained about'); ?>
          <a class="ntf2 lowercase" href="/notifications/read/<?= $notif['notification_id']; ?>">
            <?= lang('comment'); ?>
          </a>
        <?php } ?>
        <?php if ($notif['notification_action_type'] == 15) { ?>
          <a class="ntf2 lowercase" href="/notifications/read/<?= $notif['notification_id']; ?>">
            <i class="icon-lightbulb middle red"></i>
            <?= lang('audit'); ?>
          </a>
          |
          <a class="ntf2 lowercase" href="/admin/users/<?= $notif['notification_sender_id']; ?>/edit">
            <?= $notif['user_login']; ?>
          </a>
          |
          <a class="ntf2 lowercase" href="/admin/audits">
            <?= lang('admin'); ?>
          </a>
        <?php } ?>
        <span class="lowercase">
          <?php if ($notif['notification_action_type'] == 4) { ?>
            <i class="icon-commenting-o middle"></i>
            <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>"><?= $notif['user_login']; ?></a>
            <?= lang('wrote'); ?>
            <a class="ntf2" href="/notifications/read/<?= $notif['notification_id']; ?>">
              <?= lang('comment'); ?>
            </a>
            <?= lang('to your answer'); ?>
          <?php } ?>
        </span>
        <span class="size-14 gray"> — <?= $notif['notification_add_time']; ?></span>
        <?php if ($notif['notification_read_flag'] == 0) { ?>&nbsp;<sup class="red">✔</sup><?php } ?>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'no notifications yet']); ?>
  <?php } ?>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('info-notifications')]); ?>