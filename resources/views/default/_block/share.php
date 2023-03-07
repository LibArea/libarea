<div class="dialog-container" id="id-share" aria-hidden="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-description">
  <div class="dialog-overlay" data-a11y-dialog-hide></div>
  <div class="dialog-content" role="document">
    <button data-a11y-dialog-hide class="dialog-close" aria-label="Close this dialog window">
      &times;
    </button>

    <h2 class="uppercase-box"><?= $title; ?></h2>

    <fieldset class="flex items-center gap">
      <input class="w-100" id="inputText" value="<?= $url; ?>">    
      <button class="btn btn-primary" id="copyText">
        <svg class="icons">
          <use xlink:href="/assets/svg/icons.svg#copy"></use>
        </svg>
      </button>
    </fieldset>

    <div class="flex gap">

      <div class="bg-fb">
        <svg class="icons icon-share fb" data-social="facebook">
          <use xlink:href="/assets/svg/icons.svg#fb"></use>
        </svg>
      </div>

      <div class="bg-tw">
        <svg class="icons icon-share tw" data-social="twitter">
          <use xlink:href="/assets/svg/icons.svg#tw"></use>
        </svg>
      </div>

      <div class="bg-vk">
        <svg class="icons icon-share vk" data-social="vkontakte">
          <use xlink:href="/assets/svg/icons.svg#vk"></use>
        </svg>
      </div>

      <div class="bg-ok">
        <svg class="icons icon-share ok" data-social="odnoklassniki">
          <use xlink:href="/assets/svg/icons.svg#ok"></use>
        </svg>
      </div>

      <div class="bg-tg">
        <svg class="icons icon-share tg" data-social="telegram">
          <use xlink:href="/assets/svg/icons.svg#telegram"></use>
        </svg>
      </div>

    </div>
  </div>
</div>

<script nonce="<?= $_SERVER['nonce']; ?>">
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