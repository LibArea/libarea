<?php if (!empty($title)) { ?><div class="mb5"><?= $title; ?><sup class="red-500">*</sup></div><?php } ?>

<textarea id="editor" name="content"><?php if (!empty($content)) { ?><?= $content; ?><?php } ?></textarea>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', function() {
    let content = '';
    const easyMDE = new EasyMDE({
      autoDownloadFontAwesome: false,
      maxHeight: '<?= $height; ?>',
      element: document.getElementById('editor'),
      imagePathAbsolute: true,
      placeholder: "<?= Translate::get('supports.markdown'); ?>...",
      imageUploadEndpoint: "/backend/upload/image/<?= $type; ?>/<?= $id; ?>",
      previewImagesInEditor: true,
      uploadImage: true,
      spellChecker: false,

      imageTexts: {
        sbInit: '<?= Translate::get('attach.files'); ?>',
        sbOnDragEnter: '<?= Translate::get('drop.image'); ?>',
      },

      toolbar: [
        <?php foreach (Config::get('editor/buttons') as $row) { ?>
          <?php if (!empty($row['separator']) == 'separator') { ?> '|',
          <?php } else { ?> {
              name: '<?= $row['name']; ?>',
              action: <?= $row['action']; ?>,
              className: '<?= $row['className']; ?>',
              title: '<?= $row['title']; ?>',
            },
          <?php } ?>
        <?php } ?> {
          className: "bi-unlock",
          title: "<?= Translate::get('spoiler'); ?>",
          children: [{
            className: "bi-eye-slash",
            action: (e) => {
              e.codemirror.replaceSelection('{spoiler} *** {/spoiler} ');
              e.codemirror.focus();
            },
          }, {
            className: "bi-unlock",
            title: "<?= Translate::get('spoiler.auth'); ?>",
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