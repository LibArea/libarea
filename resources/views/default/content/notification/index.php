<main>
  <div class="box-flex-white">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <form action="<?= getUrlByName('notif.remove'); ?>" class="right">
      <?= Html::sumbit(Translate::get('i.read')); ?>
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
            <div class="br-bottom p5<?php if ($notif['flag'] == 0) { ?> bg-lightyellow<?php } ?>">
              <i class="<?= $n['icon']; ?> middle"></i>
              <a class="black ml5" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
              <span class="lowercase gray-600">
                <?= sprintf(Translate::get($n['lang']), '<a href="' . $url . '">', '</a>'); ?>
                — <?= Html::langDate($notif['time']); ?>
              </span>
              <?php if ($notif['flag'] == 0) { ?><sup class="ml5 red">✔</sup><?php } ?>
            </div>
          <?php } ?>
        <?php } ?>

      <?php } ?>

      <div class="p15 center gray-600"><?= Translate::get('notifications.limit'); ?></div>
    <?php } else { ?>
      <?= Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no.content'), 'icon' => 'bi-info-lg']); ?>
    <?php } ?>
  </div>
</main>
<aside>
  <div class="box-white text-sm sticky top-sm">
    <?= Translate::get('notifications.info'); ?>
  </div>
</aside>