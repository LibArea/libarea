<div class="dialog-container" id="id-share" aria-hidden="true">
  <div class="dialog-overlay" data-a11y-dialog-hide></div>
  <div class="dialog-content" role="document">
    <button data-a11y-dialog-hide class="dialog-close" aria-label="Close this dialog window">
      &times;
    </button>

    <h2 class="uppercase-box"><?= $title; ?></h2>

    <fieldset class="flex items-center gap">
      <input class="w-100" id="inputText" value="<?= $url; ?>">    
      <button class="btn btn-primary" id="copyText">
        <svg class="icon">
          <use xlink:href="/assets/svg/icons.svg#copy"></use>
        </svg>
      </button>
    </fieldset>

    <div class="flex gap">
      <svg class="icon icon-share fb" data-social="facebook">
        <use xlink:href="/assets/svg/icons.svg#fb"></use>
      </svg>

      <svg class="icon icon-share vk" data-social="vkontakte">
        <use xlink:href="/assets/svg/icons.svg#vk"></use>
      </svg>

      <svg class="icon icon-share ok" data-social="odnoklassniki">
        <use xlink:href="/assets/svg/icons.svg#ok"></use>
      </svg>

      <svg class="icon icon-share tw" data-social="twitter">
        <use xlink:href="/assets/svg/icons.svg#tw"></use>
      </svg>

      <svg class="icon icon-share tg" data-social="telegram">
        <use xlink:href="/assets/svg/icons.svg#telegram"></use>
      </svg>
    </div>
  </div>
</div>
<script nonce="<?= config('main', 'nonce'); ?>">
  document.addEventListener('DOMContentLoaded', () => {
    let dialogEl = getById('id-share');
    let dialog = new A11yDialog(dialogEl);
    
    let text = getById("inputText");
    let btn = getById("copyText");
    btn.onclick = function() {
      text.select();    
      document.execCommand("copy");
    }
  });
</script>