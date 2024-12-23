<link rel="stylesheet" href="/assets/js/editor/cherry-markdown.min.css" type="text/css">
<link as="font" href="/assets/js/editor/ch-icon.woff2">
<?php if (!empty($title)) : ?><?= $title; ?>:<?php endif; ?>
<div name="content" id="markdown-container"></div>
<div class="source none">
	<pre><?php if (!empty($content)) : ?><?= $content; ?><?php endif; ?></pre>
</div>

<script src="/assets/js/editor/cherry-markdown.core.js"></script>
<script nonce="<?= config('main', 'nonce'); ?>">
	var mdText = document.querySelector('.source pre')?.innerText;

	var cherry = new Cherry({
		id: 'markdown-container',
		value: mdText,

		<?= insert('/_block/form/editor/localization'); ?>

		togglePreview: false,

		editor: {
			name: 'content',
			defaultModel: 'editOnly', // edit&preview|editOnly|previewOnly
			height: '<?= $height; ?>',
			showSuggestList: false,
		},

		toolbars: {
			toolbar: [
				'switchModel',
				'|',
				'bold',
				'italic',
				'strikethrough',
			],
			toolbarRight: ['fullScreen'],
			// Плавающая панель инструментов, при выделении текста
			bubble: ['bold', 'italic', 'strikethrough', 'quote', 'inlineCode'],
			// Панель подсказок
			float: ['h2', 'h3', '|', 'checklist', 'quote', 'table', 'link', 'code'],
		},
	});
</script>