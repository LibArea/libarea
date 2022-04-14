<?php if (!empty($title)) : ?><div class="mb5"><?= $title; ?><sup class="red">*</sup></div><?php endif; ?>

<textarea id="editor" name="content"><?php if (!empty($content)) : ?><?= $content; ?><?php endif; ?></textarea>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', function() {
    let content = '';
    const easyMDE = new EasyMDE({
      autoDownloadFontAwesome: false,
      maxHeight: '<?= $height; ?>',
      element: document.getElementById('editor'),
      imagePathAbsolute: true,
      placeholder: "<?= __('supports.markdown'); ?>...",
      imageUploadEndpoint: "/backend/upload/<?= $type; ?>/<?= $id; ?>",
      previewImagesInEditor: true,
      uploadImage: true,
      spellChecker: false,

      imageTexts: {
        sbInit: '<?= __('attach.files'); ?>',
        sbOnDragEnter: '<?= __('drop.image'); ?>',
      },

      toolbar: [
        <?php foreach (Config::get('editor/buttons') as $row) : ?>
          <?php if (!empty($row['separator']) == 'separator') : ?> '|',
          <?php else : ?> {
              name: '<?= $row['name']; ?>',
              action: <?= $row['action']; ?>,
              className: '<?= $row['className']; ?>',
              title: '<?= $row['title']; ?>',
            },
          <?php endif; ?>
        <?php endforeach; ?> {
          className: "bi-unlock",
          title: "<?= __('spoiler'); ?>",
          children: [{
            className: "bi-eye-slash",
            action: (e) => {
              e.codemirror.replaceSelection('{spoiler} *** {/spoiler} ');
              e.codemirror.focus();
            },
          }, {
            className: "bi-unlock",
            title: "<?= __('spoiler.auth'); ?>",
            action: (e) => {
              e.codemirror.replaceSelection('{auth} *** {/auth}');
              e.codemirror.focus();
            },
          }],
        }
      ],
      initialValue: content
    });
  });
</script>