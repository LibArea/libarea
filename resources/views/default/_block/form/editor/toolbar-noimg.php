<link rel="stylesheet" href="/assets/js/editor/cherry-markdown.min.css" type="text/css">
<link as="font" href="/assets/js/editor/ch-icon.woff2">
<?php if (!empty($title)) : ?><?= $title; ?>:<?php endif; ?>
<div name="content" id="markdown-container"></div>
<textarea id="source" class="none"><?php if (!empty($content)) : ?><?= $content; ?><?php endif; ?></textarea>

<script src="/assets/js/editor/cherry-markdown.core.js"></script>
<script nonce="<?= config('main', 'nonce'); ?>">
	async function fileUpload(file, callback) {
		return false;
	}

	var cherry = new Cherry({
		id: 'markdown-container',
		value: document.getElementById("source").value,
		fileUpload: fileUpload,

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