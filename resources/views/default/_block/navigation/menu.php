<?php

$type = $type ?? false;

foreach ($menu as $key => $item) :
	$tl = $item['tl'] ?? 0; ?>
	<?php if (!empty($item['hr'])) : ?>
		<?php if ($container->user()->id() > 0) : ?><li class="m15"></li><?php endif; ?>
	<?php else : ?>
		<?php if ($container->user()->tl() >= $tl) :
			$css = empty($item['css']) ? false : $item['css'];
			$isActive = $item['id'] == $type ? 'active' : false;
			$class = ($css || $isActive) ? ' class="' . $isActive . ' ' .  $css . '"'   : ''; ?>
			<li<?= $class; ?>><a href="<?= $item['url']; ?>">
					<?php if (!empty($item['icon'])) : ?><svg class="icons">
							<use xlink:href="/assets/svg/icons.svg#<?= $item['icon']; ?>"></use>
						</svg><?php endif; ?>
					<?= __($item['title']); ?></a></li>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php if ($container->user()->id() > 0) : ?>
		<?php if ($topics_user) : ?>
			<div class="flex justify-between items-center">
				<h4 class="mb5 ml5"><?= __('app.preferences'); ?></h3>

					<a class="text-sm" title="<?= __('app.edit'); ?>" href="<?= url('setting.preferences'); ?>">
						<sup><svg class="icons gray-600">
								<use xlink:href="/assets/svg/icons.svg#edit"></use>
							</svg></sup>
					</a>
			</div>
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
					<?= $topic['facet_title']; ?> <?= $blog; ?>
				</a>
			</li>
		<?php endforeach; ?>

		<?php if ($i < 1) : ?>
			<div class="mt15 ml10">
				<a class="red text-sm" href="<?= url('setting.preferences'); ?>">
					<span class="red">+</span> <?= __('app.add'); ?></a>
				<span class="text-sm lowercase ml20 gray-600 "><?= __('app.preferences'); ?>...</span>
			</div>
		<?php endif; ?>

	<?php endif; ?>