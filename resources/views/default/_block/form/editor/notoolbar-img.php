<link rel="stylesheet" href="/assets/js/editor/cherry-markdown.min.css" type="text/css">
<link as="font" href="/assets/js/editor/ch-icon.woff2">
<?php if (!empty($title)) : ?><?= $title; ?>:<?php endif; ?>
<div name="content" id="markdown-container" spellcheck="true"></div>
<div class="source none">
	<pre><?php if (!empty($content)) : ?><?= $content; ?><?php endif; ?></pre>
</div>

<script src="/assets/js/editor/cherry-markdown.core.js"></script>
<script nonce="<?= config('main', 'nonce'); ?>">
	async function fileUpload(file, callback) {
		const formData = new FormData();
		formData.append('file', file);

		try {
			const response = await fetch('/backend/upload/<?= $type; ?>/<?= $id; ?>', {
				method: 'POST',
				body: formData
			});
			const data = await response.json();
			callback(data.data.filePath);
		} catch (err) {
			console.error('Ошибка:', err);
		}
	}

	var customMenuA = Cherry.createMenuHook('{cut}', {
		iconName: '',
		onClick: function(selection) {
			return `{cut}`;
		}
	});

	var mdText = document.querySelector('.source pre')?.innerText;

	var cherry = new Cherry({
		id: 'markdown-container',
		value: mdText,

		fileUpload: fileUpload,

		fileTypeLimitMap: {
			image: 'image/*',
		},
		multipleFileSelection: {
			image: false,
		},

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
				image: "<?= __('app.foto'); ?>"
			},
		},

		togglePreview: false,

		editor: {
			name: 'content',
			defaultModel: 'editOnly', // edit&preview|editOnly|previewOnly
			height: '<?= $height; ?>',
			showSuggestList: false,
			codemirror: {
				autofocus: false,
			},
			keepDocumentScrollAfterInit: true,
		},

		toolbars: {
			// Определите верхнюю панель инструментов
			// toolbar: ['bold','italic','strikethrough','|','list', 'image'],
			// Выключить верхнюю панель
			showToolbar: false,
			// Определите боковую панель, по умолчанию она пуста
			sidebar: ['togglePreview'],
			// Панель в правом верхнем углу
			toolbarRight: ['togglePreview'], //предварительный просмотр
			// Плавающую панель инструментов, при выделении текста
			bubble: ['bold', 'italic', 'strikethrough', 'quote', 'inlineCode'],
			// Панель подсказок
			float: ['h2', 'h3', '|', 'checklist', 'quote', 'code', 'image'],
		},
	});
</script>