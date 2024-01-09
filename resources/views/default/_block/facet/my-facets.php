<div class="box bg-lightgray">
	<h4 class="uppercase-box"><?= __('app.reading'); ?>
		<?php if ($topics_user) : ?>
			<a class="gray-600 text-sm" title="<?= __('app.edit'); ?>" href="<?= url('setting', ['type' => 'preferences']); ?>">
				<sup><svg class="icons">
						<use xlink:href="/assets/svg/icons.svg#edit"></use>
					</svg></sup>
			</a>
		<?php endif; ?>
	</h4>

	<ul>
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
					<?= Img::image($topic['facet_img'], $topic['facet_title'], 'img-base', 'logo', 'max'); ?>
					<span class="middle"><?= $topic['facet_title']; ?> <?= $blog; ?></span>
				</a>
				<?php if ($topic['facet_type'] == 'topic') : ?>
					<a class="gray-600 bg-white" title="<?= __('app.add_post'); ?>" href="<?= url('content.add', ['type' => 'post']); ?>/<?= $topic['facet_id']; ?>">
						<svg class="icons">
							<use xlink:href="/assets/svg/icons.svg#plus"></use>
						</svg>
					</a>
				<?php else : ?>
					<?php if (UserData::getUserId() == $topic['facet_user_id']) : ?>
						<a class="gray-600 bg-white" title="<?= __('app.add_post'); ?>" href="<?= url('content.add', ['type' => 'post']); ?>/<?= $topic['facet_id']; ?>">
							<svg class="icons">
								<use xlink:href="/assets/svg/icons.svg#plus"></use>
							</svg>
						</a>
					<?php endif; ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>

		<?php if ($i < 1) : ?>
			<a class="red text-sm" href="<?= url('setting', ['type' => 'preferences']); ?>">
				+ <?= __('app.add'); ?></a>
			<span class="text-sm lowercase gray-600 "><?= __('app.preferences'); ?>...</span>
		<?php endif; ?>
	</ul>
</div>