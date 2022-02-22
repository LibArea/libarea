<?= Tpl::import('/content/menu', ['data' => $data, 'user' => $user]); ?>

<?php 
    $icon = [
        1 => 'bi-envelope green-600', // сообщение 
        3 => 'bi-reply gray-400', //  ответ
        4 => 'bi-chat-dots', // комментарий
        10 => 'bi bi-person', // обращение в постах (@login)
        11 => 'bi bi-person', // в ответах (@login)
        12 => 'bi bi-person', // в комментариях (@login)
        15 => 'bi-exclamation-diamond red-500', // аудит
        20 => 'bi-exclamation-diamond red-500', // флаг система
        32 => 'bi-link-45deg sky-500', // сайт добавлен
        33 => 'bi-link-45deg sky-500', // сайт изменен и поменял статус
        34 => 'bi-link-45deg sky-500', // ваш сайт добавлен
    ];
?>

<main class="col-span-7 mb-col-12">
  <div class="box-flex-white">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <form action="<?= getUrlByName('notif.remove'); ?>" class="right">
      <?= sumbit(Translate::get('i read')); ?>
    </form>
  </div>

  <div class="bg-white mb15">
    <?php if (!empty($data['notifications'])) { ?>
      <?php foreach ($data['notifications'] as  $notif) { ?>
        <?php 
            $url = getUrlByName('notif.read', ['id' => $notif['notif_id']]); 
            $profile = getUrlByName('profile', ['login' => $notif['login']]); 
        ?>
        
        <div class="br-bottom p5<?php if ($notif['flag'] == 0) { ?> bg-sky-50<?php } ?>">
          <i class="bi <?= $icon[$notif['type']] ?> middle"></i>

          <?php if ($notif['type'] == 1) { ?>
            <a class="gray ml5" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
            <?= Translate::get('wrote to you'); ?>
            <a href="<?= $url; ?>"><?= Translate::get('message'); ?></a>
          <?php } ?>

          <?php if ($notif['type'] == 2) { ?><?= Translate::get('wrote a post'); ?><?php } ?>

          <?php if ($notif['type'] == 3) { ?>
            <a class="gray ml5" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
            <a class="ntf2 lowercase" href="<?= $url; ?>"><?= Translate::get('replied to post'); ?></a>
          <?php } ?>

          <?php if ($notif['type'] == 10 || $notif['type'] == 11 || $notif['type'] == 12) { ?>
            <a class="gray ml5" href="<?= $profile; ?>">@<?= $notif['login']; ?></a>
            <?= Translate::get('appealed to you'); ?>
            <a class="ntf2 lowercase" href="<?= $url; ?>">
              <?php if ($notif['type'] == 10) { ?>
                <?= Translate::get('in post'); ?>
              <?php } elseif ($notif['type'] == 11) { ?>
                <?= Translate::get('in answer'); ?>
              <?php } else { ?>
                <?= Translate::get('in the comment'); ?>
              <?php } ?>
            </a>
          <?php } ?>
          <?php if ($notif['type'] == 20) { ?>
            <a class="gray ml5" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
            <?= Translate::get('complained about'); ?>
            <a class="ntf2 lowercase" href="<?= $url; ?>"><?= Translate::get('content'); ?></a>
          <?php } ?>
          <?php if ($notif['type'] == 15) { ?>
            <a class="ntf2 lowercase" href="<?= $url; ?>"><?= Translate::get('audits'); ?></a> | 
            <a class="ntf2 lowercase" href="/admin/users/<?= $notif['notification_sender_id']; ?>/edit"><?= $notif['login']; ?> </a> | 
            <a class="ntf2 lowercase" href="/admin/audits"><?= Translate::get('admin'); ?></a>
          <?php } ?>
          
          <?php if ($notif['type'] == 32) { ?>
            <a class="gray ml5" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
              <?= Translate::get('added.site'); ?>: 
              <a class="ntf2 lowercase" href="<?= $url; ?>"><?= Translate::get('content'); ?></a>
          <?php } elseif ($notif['type'] == 33) { ?>
            <a class="gray ml5" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
            <?= Translate::get('changed.site'); ?>:
            <a class="ntf2 lowercase" href="<?= $url; ?>"><?= Translate::get('content'); ?></a>
          <?php } elseif ($notif['type'] == 34) { ?>
            <?= Translate::get('approved.site'); ?>:
            <a class="ntf2 lowercase" href="<?= $url; ?>"><?= Translate::get('look'); ?></a>
          <?php } ?>
          
          <span class="lowercase">
            <?php if ($notif['type'] == 4) { ?>
              <a class="gray ml5" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
              <?= Translate::get('wrote'); ?>
              <a class="ntf2" href="<?= $url; ?>"><?= Translate::get('comment'); ?></a>
              <?= Translate::get('to your answer'); ?>
            <?php } ?>
          </span>

          <span class="text-sm gray-400 lowercase"> — <?= lang_date($notif['time']); ?></span>
          <?php if ($notif['flag'] == 0) { ?>&nbsp;<sup class="red-500">✔</sup><?php } ?>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no.content'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <?= no_content(Translate::get('notifications.limit'), 'bi bi-info-lg'); ?>
</main>
<aside class="col-span-3 mb-none">
  <div class="box-white text-sm sticky top-sm">
    <?= Translate::get('notifications.info'); ?>
  </div>
</aside>