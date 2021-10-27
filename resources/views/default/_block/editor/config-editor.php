<script nonce="<?= $_SERVER['nonce']; ?>">
   $(function() {
      var path = "/assets/editor/languages/<?= $lang; ?>";
      editormd.loadScript(path, function() {
         editor.lang = "<?= $lang; ?>";
      });
      
      var editor = editormd("markdown-view", {
         <?php if ($width100 == 'yes') { ?>
            width: "100%",
            height: "400px",
            float: "right",
         <?php } else { ?>
            height: "200px",
            readOnly: false,
            watch: false,
            // lineNumbers     : false, 
            autoFocus: false,
         <?php } ?>

         toolbarIcons: ["bold", "italic", "del", "quote", "h3", "list-ul", "|", "hr", "image", "link", "|", "code", "code-block", "|", "fullscreen", "help"],

         imageUpload: true,
         imageFormats: ["jpg", "jpeg", "gif", "png", "webp"],
         imageUploadURL: "/backend/upload/image",
         postID: "<?= $post_id; ?>",
         type: "<?= $type; ?>",

         toolbarIconsClass: {
            bold: "bi bi-type-bold",
            del: "bi bi-type-strikethrough",
            italic: "bi bi-type-italic",
            quote: "bi bi-blockquote-right",
            h3: editormd.classPrefix + "small",
          //  h4: editormd.classPrefix + "bold",
            "list-ul": "bi bi-list-task",
            hr: "bi bi-hr",
            link: "bi bi-link-45deg",
            image: "bi bi-camera",
            code: "bi bi-code-slash",
            "preformatted-text": "bi bi-code-square",
            "code-block": "bi bi-code-square",
            emoji: "bi bi-emoji-wink",
            preview: "bi bi-aspect-ratio",
            fullscreen: "bi bi-arrows-fullscreen",
            help: "bi bi-info-lg",
         },
         toolbarIconTexts: {},
         path: "/assets/editor/lib/"
      });

   });
</script>