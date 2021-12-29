<div class="mb10 mt15">
  <div id="ag-redactor" class="editorSection"><?php if (!empty($content)) { ?><?= $content; ?><?php } ?></div>
  <input type="hidden" name="content" class="text">
</div>
<?php $lang = $uid['user_lang']; ?>
<script src="/assets/js/editor/i18n/<?= $lang; ?>.js" charset="utf-8"></script>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', function() {

    toastui.Editor.setLanguage(['<?= $lang; ?>'], LANG);

    let dark = document.querySelector('.bg-gray-100.dark');
    let viewerEl = document.querySelector('.editorSection');
    let body = viewerEl.innerHTML.trim();
    body = body.replace(/&gt;/gi, ">");

    if (viewerEl == null) {
      return;
    }

    let previewStyle = '<?= $preview; ?>';
    let height = '<?= $height; ?>';
    if (window.innerWidth < 721) {
      previewStyle = 'tab';
      height = '200px';
    }

    const editor = new toastui.Editor({
      el: viewerEl,
      usageStatistics: false,
      height: height,
      initialEditType: '<?= Config::get('editor.initialEditType'); ?>', // wysiwyg | markdown
      previewStyle: previewStyle,
      hideModeSwitch: '<?= Config::get('editor.hideModeSwitch'); ?>', // true | false
      initialValue: body,
      theme: '<?= Request::getCookie('dayNight'); ?>',
      language: '<?= $lang; ?>',
      autofocus: false,
      toolbarItems: [
        ['heading', 'bold', 'italic', 'strike'],
        ['hr', 'quote'],
        ['ul'],
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
          let alt_text = document.getElementById("toastuiAltTextInput").value;
          const ajax = new XMLHttpRequest()
          ajax.open('POST', '/backend/upload/image', true)
          ajax.send(formData)
          ajax.onreadystatechange = function() {
            if (ajax.readyState === 4) {
              if ((ajax.status >= 200 && ajax.status < 300) || ajax.status === 304) {
                callback(ajax.responseText, alt_text);
              }
            }
          }
        }
      }
    });
    editor.getMarkdown();
  });
</script>