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

		locale: '<?= Translate::getLang(); ?>',
		locales: {
			<?= Translate::getLang(); ?>: {
				bold: "<?= __('app.bold'); ?>",
				italic: "<?= __('app.italic'); ?>",
				strikethrough: "<?= __('app.strikethrough'); ?>",
				h2: "<?= __('app.h2'); ?>",
				h3: "<?= __('app.h3'); ?>",
				checklist: "<?= __('app.'); ?>",
				quote: "<?= __('app.quote'); ?>",
				table: "<?= __('app.table'); ?>",
				inlineCode: "<?= __('app.inline_code'); ?>",
				codeBlock: "<?= __('app.code_block'); ?>",
				link: "<?= __('app.link'); ?>",
				image: "<?= __('app.foto'); ?>",
				switchModel: "<?= __('app.switching'); ?>",
				switchPreview: "<?= __('app.view'); ?>",
				switchEdit: "<?= __('app.editing'); ?>",
				fullScreen: "<?= __('app.fullscreen'); ?>",
			},
		},

		togglePreview: false,

		editor: {
			name: 'content',
			defaultModel: 'editOnly', // edit&preview|editOnly|previewOnly
			height: '<?= $height; ?>',
			showSuggestList: false,
			codemirror: {
				// placeholder: "<?= __('app.text'); ?>...",
			},
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