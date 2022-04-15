<main class="col-two">
  <div class="box-flex bg-violet justify-between">
    <p class="m0"><?= __($data['sheet']); ?></p>
    <form action="<?= getUrlByName('notif.remove'); ?>" class="right">
      <?= Html::sumbit(__('i.read')); ?>
    </form>
  </div>

  <div class="bg mb15">
    <?php if (!empty($data['notifications'])) : ?>
      <?php foreach ($data['notifications'] as  $notif) :
        $url = getUrlByName('notif.read', ['id' => $notif['notif_id']]);
        $profile = getUrlByName('profile', ['login' => $notif['login']]);
      ?>

        <?php foreach (Config::get('notification') as $key => $n) : ?>
          <?php if ($n['id'] == $notif['type']) : ?>
            <div class="br-bottom p5<?php if ($notif['flag'] == 0) : ?> bg-lightyellow<?php endif; ?>">
              <i class="<?= $n['icon']; ?> middle"></i>
              <a class="black ml5" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
              <span class="lowercase gray-600">
                <?= sprintf(__($n['lang']), '<a href="' . $url . '">', '</a>'); ?>
                — <?= Html::langDate($notif['time']); ?>
              </span>
              <?php if ($notif['flag'] == 0) : ?><sup class="ml5 red">✔</sup><?php endif; ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>

      <?php endforeach; ?>

      <div class="p15 center gray-600"><?= __('notifications.limit'); ?></div>
    <?php else : ?>
      <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no.content'), 'icon' => 'bi-info-lg']); ?>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box bg-violet text-sm sticky top-sm">
    <?= __('notifications.info'); ?>
  </div>
</aside>