<link rel="stylesheet" href="/assets/js/editor/cherry-markdown.min.css" type="text/css">
<link as="font" href="/assets/js/editor/ch-icon.woff2">
<?php if (!empty($title)) : ?><?= $title; ?>:<?php endif; ?>

<div name="content" id="markdown-container"></div>
<div class="source none"><pre><?php if (!empty($content)) : ?><?= $content; ?><?php endif; ?></pre></div> 
 
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
				name: 'details',
				onclick: (event) => {
					return cherry.insert(' {details} *** {/details} ');
				}
			},
			{
				noIcon: true,
				name: 'auth',
				onclick: (event) => {
					return cherry.insert(' {auth} *** {/auth} ');
				}
			},
		]
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
			customMenu: {
				cut: customMenuA,
				help: customMenuB,
			},

			// Определим верхнюю панель инструментов
			toolbar: ['switchModel', '|', 'bold', 'italic', 'strikethrough', 'image', '|', 'cut', 'help'],
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