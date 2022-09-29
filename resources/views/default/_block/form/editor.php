<?php if (!empty($title)) : ?><div class="mb5"><?= $title; ?><sup class="red">*</sup></div><?php endif; ?>

<textarea id="editor" name="content"><?php if (!empty($content)) : ?><?= $content; ?><?php endif; ?></textarea>

<div id="editor"></div>
<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', function() {
    let content = '';
    const easyMDE = new EasyMDE({
      autoDownloadFontAwesome: false,
      maxHeight: '<?= $height; ?>',
      element: document.getElementById('editor'),
      imagePathAbsolute: true,
      placeholder: "<?= __('app.markdown'); ?>...",
      imageUploadEndpoint: "/backend/upload/<?= $type; ?>/<?= $id; ?>",
      previewImagesInEditor: true,
      uploadImage: true,
      spellChecker: false,
      <?php if (!empty($autosave)) : ?>
        autosave: {
          enabled: true,
          uniqueId: '<?= $autosave; ?>',
          delay: 500,
          submit_delay: 1000,
          text: "&nbsp;"
        },
      <?php endif; ?>
      imageTexts: {
        sbInit: '<?= __('app.attach_files'); ?>',
        sbOnDragEnter: '<?= __('app.drop_image'); ?>',
      },

      toolbar: [
        <?php
        $conf_editor = config('editor/buttons');
        if (!empty($message)) $conf_editor = config('editor/message');
        ?>
        <?php foreach ($conf_editor as $row) : ?>
          <?php if (!empty($row['separator']) == 'separator') : ?> '|',
          <?php else : ?> {
              name: '<?= $row['name']; ?>',
              action: <?= $row['action']; ?>,
              icon: '<?= $row['icon']; ?>',
              title: '<?= $row['title']; ?>',
            },
          <?php endif; ?>
        <?php endforeach; ?>
        <?php if (!empty($cut)) : ?> {
            icon: '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#cut"></use></svg>',
            title: "<?= __('app.crop_post'); ?>",
            action: (e) => {
              e.codemirror.replaceSelection('{cut}');
              e.codemirror.focus();
            },
          },
        <?php endif; ?> {
          icon: '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#lock"></use></svg>',
          title: "<?= __('app.spoiler'); ?>",
          children: [{
            icon: '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#eyeglass"></use></svg>',
            action: (e) => {
              e.codemirror.replaceSelection('{details} *** {/details} ');
              e.codemirror.focus();
            },
          }, {
            icon: '<svg class="icons"><use xlink:href="/assets/svg/icons.svg#lock"></use></svg>',
            title: "<?= __('app.spoiler_auth'); ?>",
            action: (e) => {
              e.codemirror.replaceSelection('{auth} *** {/auth} ');
              e.codemirror.focus();
            },
          }],
        }
      ],
      initialValue: content
    });
  });
</script>