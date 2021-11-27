<div class="mb10 mt15">
  <script type="text/x-template"><?php if (!empty($content)) { ?><?= $content; ?><?php } ?></script>
  <div class="editorSection"></div>
  <input type="hidden" name="content" class="text">
</div>

<script src="/assets/js/editor/i18n/<?= $lang; ?>.js" charset="utf-8"></script>

<script nonce="<?= $_SERVER['nonce']; ?>">
  $(document).ready(function() {

    toastui.Editor.setLanguage(['<?= $lang; ?>'], LANG);

     let dark = document.querySelector('.bg-gray-100.dark');
 
     $('.editorSection').each(function(index, node) {
      var initialValue = $(node).prev().html().trim().replace(/<!--REPLACE:script-->/gi, 'script');

      let editor = new toastui.Editor({
        el: node,
        usageStatistics: false,
        height: '<?= $height; ?>',
        initialEditType: 'markdown',
        previewStyle: '<?= $preview; ?>',
        autofocus: false,
        hideModeSwitch: true, 
        initialValue: initialValue,
        theme: '<?= Request::getCookie('dayNight'); ?>',
        language: '<?= $lang; ?>',
        toolbarItems: [
          ['heading', 'bold', 'italic', 'strike'],
          ['hr', 'quote'],
          ['ul', 'ol'],
          ['table', 'image', 'link'],
          ['code', 'codeblock'],
        ],
        events: {
          change: function() {
            let text = editor.getMarkdown();
            inputelement = document.querySelector('input.text');
            inputelement.value = text;
          },
        },
        hooks: {
          addImageBlobHook: function(file, callback) {

            const formData = new FormData()
            formData.append('file', file, file.name)

            const ajax = new XMLHttpRequest()
            ajax.open('POST', '/backend/upload/image', true)
            ajax.send(formData)
            ajax.onreadystatechange = function() {
              if (ajax.readyState === 4) {
                if ((ajax.status >= 200 && ajax.status < 300) || ajax.status === 304) {
                  callback(ajax.responseText, 'alt_text');
                }
              }
            }
          }
        }
      });

      editor.getMarkdown();

    });
  });
</script>