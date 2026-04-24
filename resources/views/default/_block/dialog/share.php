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
	    <?= icon('icons-share', 'copy'); ?>
      </button>
    </fieldset>

    <div class="flex gap">
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $url; ?>" target="_blank">
	    <?= icon('icons-share', 'fb', 48, 'icon fb'); ?>
      </a>

      <a href="https://vk.com/share.php?url=<?= $url; ?>" target="_blank">
	    <?= icon('icons-share', 'vk', 48, 'icon vk'); ?>
      </a>

      <a href="https://connect.ok.ru/offer?url=<?= $url; ?>" target="_blank">
	    <?= icon('icons-share', 'ok', 48, 'icon ok'); ?>
      </a>

      <a href="https://twitter.com/intent/tweet?url=<?= $url; ?>" target="_blank">
	    <?= icon('icons-share', 'tw', 48, 'icon tw'); ?>
      </a>

      <a href="https://t.me/share/url?url=<?= $url; ?>" target="_blank">
	    <?= icon('icons-share', 'tg', 48, 'icon tg'); ?>
      </a>
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