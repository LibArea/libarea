<link rel="stylesheet" href="/assets/js/editor/cherry-markdown.min.css" type="text/css">
<link as="font" href="/assets/js/editor/ch-icon.woff2">
<?php if (!empty($title)) : ?><?= $title; ?>:<?php endif; ?>

<div name="content" id="markdown-container"></div>
<textarea id="source" class="none"><?php if (!empty($content)) : ?><?= $content; ?><?php endif; ?></textarea>

<script src="/assets/js/editor/cherry-markdown.core.js"></script>
<!--script src="/assets/js/editor/editorjs-like.js"></script-->
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
	};

	var customMenuA = Cherry.createMenuHook('{cut}', {
		iconName: '',
		onClick: function(selection) {
			return `{cut}`;
		}
	});

	var customMenuB = Cherry.createMenuHook('<?= __('app.spoiler'); ?>', {
		iconName: 'help',
		subMenuConfig: [{
				noIcon: true,
				name: 'ℹ️ note',
				onclick: (event) => {
					return cherry.insert(' ::: note \n <?= __('app.write_text'); ?> \n :::');
				}
			}, {
				noIcon: true,
				name: '💡 tip',
				onclick: (event) => {
					return cherry.insert(' ::: tip \n <?= __('app.write_text'); ?> \n ::: ');
				}
			},
			{
				noIcon: true,
				name: '⚠️ warning',
				onclick: (event) => {
					return cherry.insert(' ::: warning \n <?= __('app.write_text'); ?> \n ::: ');
				}
			},
		]
	});

	var cherry = new Cherry({
		id: 'markdown-container',
		value: document.getElementById("source").value,

		fileUpload: fileUpload,

		fileTypeLimitMap: {
			image: 'image/*',
		},
		multipleFileSelection: {
			image: false,
		},

        <?= insert('/_block/form/editor/localization'); ?>

		togglePreview: false,

		editor: {
			name: 'content',
			defaultModel: 'editOnly', // edit&preview|editOnly|previewOnly
			height: '<?= $height; ?>',
			showSuggestList: false,
		},

		toolbars: {
			customMenu: {
				cut: customMenuA,
				help: customMenuB,
			},

			// Определим верхнюю панель инструментов
			toolbar: ['switchModel', '|', 'bold', 'italic', 'strikethrough', 'image', '|', 'cut', 'help'],
			
			// Выключить верхнюю панель
			// showToolbar: false,
			
			toolbarRight: ['fullScreen'],

			// Выключить верхнюю панель
			// showToolbar: false,
			// Определим боковую панель, по умолчанию она пуста
			// sidebar: ['togglePreview'],

			// Плавающую панель инструментов, при выделении текста
			bubble: ['bold', 'italic', 'strikethrough', 'quote', 'table', 'inlineCode'],
			// Панель подсказок
			float: ['h2', 'h3', '|', 'checklist', 'quote', 'table', 'code', 'image'],
		},
	});
</script>