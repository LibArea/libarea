<div class="flex gap-lg items-center">
<?php if (!$container->user()->active()) : ?>
	<div id="toggledark" class="gray-600">
	  <svg class="icon large">
		<use xlink:href="/assets/svg/icons.svg#sun"></use>
	  </svg>
	</div>
	<?php if (config('general', 'invite') == false) : ?>
	  <a class="gray center mb-none block" href="<?= url('register'); ?>">
		<?= __('app.registration'); ?>
	  </a>
	<?php endif; ?>
	<a class="btn btn-outline-primary" href="<?= url('login'); ?>">
	  <?= __('app.sign_in'); ?>
	</a>
<?php else : ?>
	<?= Html::addPost($facet_id ?? false); ?>

	<a id="toggledark" class="gray-600">
	  <svg class="icon large">
		<use xlink:href="/assets/svg/icons.svg#sun"></use>
	  </svg>
	</a>

	<div class="relative">
	  <div id="el_notif" class="none"></div>
	  <a id="notif" class="add-notif gray-600 relative">
		<svg class="icon large">
		  <use xlink:href="/assets/svg/icons.svg#bell"></use>
		</svg>
		<span class="number-notif"></span>
	  </a>
	</div>

	<div class="relative">
	  <div class="trigger pointer">
		<?= Img::avatar($container->user()->avatar(), $container->user()->login(), 'img-base', 'small'); ?>
	  </div>
	  <div class="dropdown user">
		<?= insert('/_block/navigation/config/user-menu'); ?>
	  </div>
	</div>
<?php endif; ?>
</div>