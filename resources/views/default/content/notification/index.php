<main>
  <div class="box-flex-white">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <form action="<?= getUrlByName('notif.remove'); ?>" class="right">
      <?= sumbit(Translate::get('i.read')); ?>
    </form>
  </div>

  <div class="bg-white mb15">
    <?php if (!empty($data['notifications'])) { ?>
      <?php foreach ($data['notifications'] as  $notif) {
        $url = getUrlByName('notif.read', ['id' => $notif['notif_id']]);
        $profile = getUrlByName('profile', ['login' => $notif['login']]);
      ?>

        <?php foreach (Config::get('notification') as $key => $n) { ?>
          <?php if ($n['id'] == $notif['type']) { ?>
            <div class="br-bottom p5<?php if ($notif['flag'] == 0) { ?> bg-sky-50<?php } ?>">
              <i class="<?= $n['icon']; ?> middle"></i>
              <a class="black ml5" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
              <span class="lowercase gray-400">
                <?= sprintf(Translate::get($n['lang']), '<a href="' . $url . '">', '</a>'); ?>
                — <?= lang_date($notif['time']); ?>
              </span>
              <?php if ($notif['flag'] == 0) { ?><sup class="ml5 red">✔</sup><?php } ?>
            </div>
          <?php } ?>
        <?php } ?>

      <?php } ?>

      <div class="p15 center gray-400"><?= Translate::get('notifications.limit'); ?></div>
    <?php } else { ?>
      <?= no_content(Translate::get('no.content'), 'bi-info-lg'); ?>
    <?php } ?>
  </div>
</main>
<aside>
  <div class="box-white text-sm sticky top-sm">
    <?= Translate::get('notifications.info'); ?>
  </div>
</aside>