<div class="sticky col-span-2 justify-between no-mob">
  <?= import('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <form action="<?= getUrlByName('notif.remove'); ?>" class="right">
      <?= sumbit(Translate::get('i read')); ?> 
    </form>
  </div>

  <?php if (!empty($data['notifications'])) { ?>
    <?php foreach ($data['notifications'] as  $notif) { ?>

      <div class="br-bottom p5<?php if ($notif['notification_read_flag'] == 0) { ?> bg-gray-200<?php } ?>">
        <?php if ($notif['notification_action_type'] == 1) { ?>
          <i class="bi bi-envelope middle"></i>
          <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>">
            <?= $notif['user_login']; ?>
          </a>
          <?= Translate::get('wrote to you'); ?>
          <a href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
            <?= Translate::get('message'); ?>
          </a>
        <?php } ?>
 
        <?php if ($notif['notification_action_type'] == 2) { ?>
          <?= Translate::get('wrote a post'); ?>
        <?php } ?>

        <?php if ($notif['notification_action_type'] == 3) { ?>
          <i class="bi bi-reply middle"></i>
          <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>">
            <?= $notif['user_login']; ?>
          </a>
          <a class="ntf2 lowercase" href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
            <?= Translate::get('replied to post'); ?>
          </a>
        <?php } ?>

        <?php if ($notif['notification_action_type'] == 10 || $notif['notification_action_type'] == 11 || $notif['notification_action_type'] == 12) { ?>
          <i class="bi bi-person middle"></i>
          <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>">@<?= $notif['user_login']; ?></a>
          <?= Translate::get('appealed to you'); ?>
          <a class="ntf2 lowercase" href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
            <?php if ($notif['notification_action_type'] == 10) { ?>
              <?= Translate::get('in post'); ?>
            <?php } elseif ($notif['notification_action_type'] == 11) { ?>
              <?= Translate::get('in answer'); ?>
            <?php } else { ?>
              <?= Translate::get('in the comment'); ?>
            <?php } ?>
          </a>
        <?php } ?>
        <?php if ($notif['notification_action_type'] == 20) { ?>
          <i class="bi bi-exclamation-diamond middle red"></i>
          <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>"><?= $notif['user_login']; ?></a>
          <?= Translate::get('complained about'); ?>
          <a class="ntf2 lowercase" href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
            <?= Translate::get('comment'); ?>
          </a>
        <?php } ?>
        <?php if ($notif['notification_action_type'] == 15) { ?>
          <a class="ntf2 lowercase" href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
            <i class="bi bi-exclamation-diamond middle red"></i>
            <?= Translate::get('audit'); ?>
          </a>
          |
          <a class="ntf2 lowercase" href="/admin/users/<?= $notif['notification_sender_id']; ?>/edit">
            <?= $notif['user_login']; ?>
          </a>
          |
          <a class="ntf2 lowercase" href="/admin/audits">
            <?= Translate::get('admin'); ?>
          </a>
        <?php } ?>
        <span class="lowercase">
          <?php if ($notif['notification_action_type'] == 4) { ?>
            <i class="bi bi-chat-dots middle"></i>
            <a class="gray ml5" href="<?= getUrlByName('user', ['login' => $notif['user_login']]); ?>"><?= $notif['user_login']; ?></a>
            <?= Translate::get('wrote'); ?>
            <a class="ntf2" href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
              <?= Translate::get('comment'); ?>
            </a>
            <?= Translate::get('to your answer'); ?>
          <?php } ?>
        </span>
        <span class="size-14 gray"> — <?= $notif['notification_add_time']; ?></span>
        <?php if ($notif['notification_read_flag'] == 0) { ?>&nbsp;<sup class="red">✔</sup><?php } ?>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no notifications yet'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('info-notifications')]); ?>