<?php
$type = $type ?? false;
foreach ($list as $key => $item) :
	$tl = $item['tl'] ?? 0; ?>
	<?php if (!empty($item['hr'])) : ?>
		<?php if (UserData::checkActiveUser()) : ?><li class="m15"></li><?php endif; ?>
	<?php else : ?>
		<?php if (UserData::getRegType($tl)) :
			$css = empty($item['css']) ? false : $item['css'];
			$isActive = $item['id'] == $type ? 'active' : false;
			$class = ($css || $isActive) ? ' class="' . $isActive . ' ' .  $css . '"'   : ''; ?>
			<li<?= $class; ?>><a href="<?= $item['url']; ?>">
					<?php if (!empty($item['icon'])) : ?><svg class="icons">
							<use xlink:href="/assets/svg/icons.svg#<?= $item['icon']; ?>"></use>
						</svg><?php endif; ?>
					<?= $item['title']; ?></a></li>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php if (UserData::checkActiveUser()) : ?>
		<br>
		<?php if ($topics_user) : ?>
			<a class="right text-sm" title="<?= __('app.edit'); ?>" href="<?= url('setting', ['type' => 'preferences']); ?>">
				<sup><svg class="icons gray-600">
						<use xlink:href="/assets/svg/icons.svg#edit"></use>
					</svg></sup>
			</a>
		<?php endif; ?>

		<?php $i = 0;
		foreach ($topics_user as $topic) :

			if ($topic['type'] == 2) continue;

			$i++;

			$url = url('topic', ['slug' => $topic['facet_slug']]);
			$blog = '';

			if ($topic['facet_type'] == 'blog') :
				$blog = '<sup class="red">b</span>';
				$url = url('blog', ['slug' => $topic['facet_slug']]);
			endif;
		?>
			<li class="mt15 flex gap items-center justify-between">
				<a class="flex gap-min items-center" href="<?= $url; ?>">
					<?= Img::image($topic['facet_img'], $topic['facet_title'], 'img-sm', 'logo', 'max'); ?>
					<span class="middle"><?= $topic['facet_title']; ?> <?= $blog; ?></span>
				</a>
			</li>
		<?php endforeach; ?>

		<?php if ($i < 1) : ?>
			<div class="ml10">
				<a class="red text-sm" href="<?= url('setting', ['type' => 'preferences']); ?>">
					+ <?= __('app.add'); ?></a>
				<span class="text-sm lowercase ml15 gray-600 "><?= __('app.preferences'); ?>...</span>
			</div>
		<?php endif; ?>
	<?php endif; ?>