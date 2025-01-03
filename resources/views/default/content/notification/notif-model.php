<div class="dropdown notif block">
  <span class="right-close pointer">x</span>
  <div class="mt10">
    <?php if (!empty($data['notifications'])) : ?>
      <?php foreach ($data['notifications'] as  $notif) :
        $url = url('notif.read', ['id' => $notif['notif_id']]);
        $profile = url('profile', ['login' => $notif['login']]);
      ?>

        <?php foreach (config('notification', 'list') as $key => $n) : ?>
          <?php if ($n['id'] == $notif['type']) : ?>
            <div class="br-bottom flex gap-min text-sm p5<?php if ($notif['flag'] == 0) { ?> bg-yellow<?php } ?>">
              <div class="relative img-base">
                <?= Img::avatar($notif['avatar'], $notif['login'], 'img-base', 'min'); ?>
                <svg class="number-svg <?= $n['css']; ?>">
                  <use xlink:href="/assets/svg/icons.svg#<?= $n['icon']; ?>"></use>
                </svg>
              </div>
              <div>
                <a class="black ml5 nickname" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
                <span class="lowercase gray-600">
                  <?= __('app.' . $n['lang'], ['url' => '<a href="' . $url . '">', 'a' => '</a>']); ?>
                  — <?= langDate($notif['time']); ?>
                </span>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>

      <?php endforeach; ?>

      <div class="p15 lowercase">
        <a href="<?= url('notifications'); ?>"><?= __('app.notifications_page'); ?></a>
        <a href="<?= url('notif.remove'); ?>" class="right gray-600">
          <?= __('app.i_read'); ?>
        </a>
      </div>
    <?php else : ?>
      <div class="text-sm">
        <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_content'), 'icon' => 'info']); ?>
      </div>
      <div class="p15 center">
        <a href="<?= url('notifications'); ?>"><?= __('app.notifications_page'); ?></a>
      </div>
    <?php endif; ?>
  </div>
</div>