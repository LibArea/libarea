<script nonce="<?= $_SERVER['nonce']; ?>">
   $(function() {
      var path = "/assets/editor/languages/<?= LANG; ?>";
      editormd.loadScript(path, function() {
         editor.lang = editormd.defaults.lang;
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
            bold: "icon-bold",
            del: "icon-strike",
            italic: "icon-italic",
            quote: "icon-quote",
            h3: editormd.classPrefix + "light-icon-message-circle",
            h4: editormd.classPrefix + "bold",
            "list-ul": "icon-list-bullet",
            hr: "icon-minus-outline",
            link: "icon-link",
            image: "icon-camera-outline",
            code: "icon-terminal",
            "preformatted-text": "icon-terminal",
            "code-block": "icon-file-code",
            emoji: "icon crop",
            preview: "icon-device-desktop",
            fullscreen: "icon-move",
            help: "icon-info",
         },
         toolbarIconTexts: {},
         path: "/assets/editor/lib/"
      });

   });
</script>