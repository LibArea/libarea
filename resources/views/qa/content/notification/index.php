<main class="col-span-9 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <form action="<?= getUrlByName('notif.remove'); ?>" class="right">
      <?= sumbit(Translate::get('i read')); ?>
    </form>
  </div>

  <div class="bg-white br-box-gray br-rd5 p15">
  <?php if (!empty($data['notifications'])) { ?>
    <?php foreach ($data['notifications'] as  $notif) { ?>

      <div class="br-bottom p5<?php if ($notif['notification_read_flag'] == 0) { ?> bg-sky-50<?php } ?>">
        <?php if ($notif['notification_action_type'] == 1) { ?>
          <i class="bi bi-envelope middle"></i>
          <a class="gray ml5" href="/@<?= $notif['login']; ?>">
            <?= $notif['login']; ?>
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
          <a class="gray ml5" href="/@<?= $notif['login']; ?>">
            <?= $notif['login']; ?>
          </a>
          <a class="ntf2 lowercase" href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
            <?= Translate::get('replied to post'); ?>
          </a>
        <?php } ?>

        <?php if ($notif['notification_action_type'] == 10 || $notif['notification_action_type'] == 11 || $notif['notification_action_type'] == 12) { ?>
          <i class="bi bi-person middle"></i>
          <a class="gray ml5" href="/@<?= $notif['login']; ?>">@<?= $notif['login']; ?></a>
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
          <i class="bi bi-exclamation-diamond middle red-500"></i>
          <a class="gray ml5" href="/@<?= $notif['login']; ?>"><?= $notif['login']; ?></a>
          <?= Translate::get('complained about'); ?>
          <a class="ntf2 lowercase" href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
            <?= Translate::get('comment'); ?>
          </a>
        <?php } ?>
        <?php if ($notif['notification_action_type'] == 15) { ?>
          <a class="ntf2 lowercase" href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
            <i class="bi bi-exclamation-diamond middle red-500"></i>
            <?= Translate::get('audits'); ?>
          </a>
          |
          <a class="ntf2 lowercase" href="/admin/users/<?= $notif['notification_sender_id']; ?>/edit">
            <?= $notif['login']; ?>
          </a>
          |
          <a class="ntf2 lowercase" href="/admin/audits">
            <?= Translate::get('admin'); ?>
          </a>
        <?php } ?>
        <span class="lowercase">
          <?php if ($notif['notification_action_type'] == 4) { ?>
            <i class="bi bi-chat-dots middle"></i>
            <a class="gray ml5" href="/@<?= $notif['login']; ?>"><?= $notif['login']; ?></a>
            <?= Translate::get('wrote'); ?>
            <a class="ntf2" href="<?= getUrlByName('notif.read', ['id' => $notif['notification_id']]); ?>">
              <?= Translate::get('comment'); ?>
            </a>
            <?= Translate::get('to your answer'); ?>
          <?php } ?>
        </span>
        <span class="text-sm gray-400 lowercase"> — <?= lang_date($notif['notification_add_time']); ?></span>
        <?php if ($notif['notification_read_flag'] == 0) { ?>&nbsp;<sup class="red-500">✔</sup><?php } ?>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no.content'), 'bi bi-info-lg'); ?>
  <?php } ?>
  </div>
</main>
<?= Tpl::import('/_block/sidebar/lang', ['lang' => Translate::get('info-notifications')]); ?>