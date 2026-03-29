<?php
/*
 * Showing fields on the profile page
 * Показ полей на странице профиля
 */

$sidebar = [
	[
		'url'       => 'website',
		'addition'  => false,
		'title'     => 'website',
		'lang'      => 'app.url',
	],
	[
		'url'       => false,
		'addition'  => false,
		'title'     => 'location',
		'lang'      => 'app.city',
	],
	[
		'url'       => 'public_email',
		'addition'  => 'mailto:',
		'title'     => 'public_email',
		'lang'      => 'app.email',
	],
	[
		'url'       => 'github',
		'addition'  => false,
		'title'     => 'github',
		'lang'      => 'app.github',
	],
	[
		'url'       => 'skype',
		'addition'  => 'skype:',
		'title'     => 'skype',
		'lang'      => 'app.skype',
	],
	[
		'url'       => 'telegram',
		'addition'  => false,
		'title'     => 'telegram',
		'lang'      => 'app.telegram',
	],
	[
		'url'       => 'vk',
		'addition'  => false,
		'title'     => 'vk',
		'lang'      => 'app.vk',
	],
];
?>

<?php foreach ($sidebar as $block) : ?>
	<?php if ($profile[$block['title']]) : ?>
		<div class="mt5">
			<?php if ($block['url']) : ?>
				<?php if ($counts ?? 0 > 3) : ?>
					<span class="gray-600"><?= __($block['lang']); ?>:</span>
					<a href="<?php if ($block['addition']) : ?><?= $block['addition']; ?><?php endif; ?><?= $profile[$block['url']]; ?>" rel="noopener nofollow ugc">
						<span class="mr5 ml5"><?= $profile[$block['title']]; ?></span>
					</a>
				<?php endif; ?>
			<?php else : ?>
				<span class="gray-600"><?= __($block['lang']); ?>:</span>
				<span class="mr5 ml5"><?= $profile[$block['title']]; ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php endforeach; ?>