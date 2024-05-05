<main>
  <div class="box-flex bg-violet justify-between">
    <?= __('app.notifications'); ?>
    <a href="<?= url('notif.remove'); ?>" class="right">
      <?= Html::sumbit(__('app.i_read')); ?>
    </a>
  </div>

  <div class="bg-white mb15">
    <?php if (!empty($data['notifications'])) : ?>
      <?php foreach ($data['notifications'] as  $notif) :
        $url = url('notif.read', ['id' => $notif['notif_id']]);
        $profile = url('profile', ['login' => $notif['login']]);
      ?>

        <?php foreach (config('notification', 'list') as $key => $n) : ?>
          <?php if ($n['id'] == $notif['type']) : ?>
            <div class="br-bottom p5<?php if ($notif['flag'] == 0) { ?> bg-yellow<?php } ?>">
              <svg class="icons <?= $n['css']; ?>">
                <use xlink:href="/assets/svg/icons.svg#<?= $n['icon']; ?>"></use>
              </svg>
              <a class="black ml5 nickname" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
              <span class="lowercase gray-600">
                <?= __('app.' . $n['lang'], ['url' => '<a href="' . $url . '">', 'a' => '</a>']); ?>
                — <?= langDate($notif['time']); ?>
              </span>
              <?php if ($notif['flag'] == 0) : ?><sup class="ml5 red">✔</sup><?php endif; ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>

      <?php endforeach; ?>

      <div class="p15 center gray-600"><?= __('app.notifications_limit'); ?></div>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_content'), 'icon' => 'info']); ?>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('help.notifications_info'); ?>
  </div>
</aside>