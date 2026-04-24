<?php foreach ($data['notifications'] as  $notif) :
$url = url('notif.read', ['id' => $notif['notif_id']]);
$profile = url('profile', ['login' => $notif['login']]);
?>

<?php foreach (config('notification', 'list') as $key => $n) : ?>
  <?php if ($n['id'] == $notif['type']) : ?>
	<div class="br-bottom flex items-center <?= $size; ?> gap-sm p5<?php if ($notif['flag'] == 0) { ?> bg-yellow<?php } ?>">
	  <div class="relative img-base mr5">
		<?= Img::avatar($notif['avatar'], $notif['login'], 'img-base', 'min'); ?>
		<?= icon('icons', $n['icon'], 16, 'number-svg ' . $n['css']); ?>
	  </div>
	  <div>
		<a class="black nickname<?php if (Html::loginColor($notif['created_at'])) : ?> new<?php endif; ?>" href="<?= $profile; ?>"><?= $notif['login']; ?></a>
		<span class="lowercase gray-600">
		  <?= __('app.' . $n['lang'], ['url' => '<a href="' . $url . '">', 'a' => '</a>']); ?>
		  — <?= langDate($notif['time']); ?>
		</span>
	  </div>
	</div>
  <?php endif; ?>
<?php endforeach; ?>

<?php endforeach; ?>
