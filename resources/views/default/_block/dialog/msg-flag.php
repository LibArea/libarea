<?php if ($container->user()->tl() >= config('trust-levels', 'tl_add_report')) : ?>
  <script nonce="<?= config('main', 'nonce'); ?>">
    document.addEventListener('DOMContentLoaded', () => {

      let dialogEl = document.getElementById('my-dialog')
      let dialog = new A11yDialog(dialogEl)

      dialogEl.addEventListener('show', function(event) {

        let post_id = event.detail.currentTarget.dataset.post_id;
        let content_id = event.detail.currentTarget.dataset.content_id;
        let type = event.detail.currentTarget.dataset.type;

        let flag = document.getElementById("flag");
        flag.addEventListener('click', function(e) {

          dialog.hide();

          fetch("/flag/repost", {
              method: "POST",
              body: "type=" + type + "&post_id=" + post_id + "&content_id=" + content_id + "&_token=" + token,
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              }
            })
            .then((response) => {
              return;
            }).then((text) => {
              Notice('<?= __('msg.yes_repost'); ?>', 3500, {
                valign: 'bottom',
                align: 'center',
                styles: {
                  backgroundColor: 'green',
                  fontSize: '18px'
                }
              });
            });
        });
      })
    });
  </script>

  <div class="dialog-container" id="my-dialog" aria-hidden="true">
    <div class="dialog-overlay" data-a11y-dialog-hide></div>
    <div class="dialog-content" role="document">
      <button data-a11y-dialog-hide class="dialog-close" aria-label="Close this dialog window">
        &times;
      </button>

      <h2 class="uppercase-box"><?= __('app.report'); ?></h2>

      <p id="my-dialog-description">
        <?= __('app.report_info'); ?>
      </p>

      <button id="flag" class="btn btn-primary mr5" type="submit" name="button"><?= __('app.report'); ?></button>
      <span data-a11y-dialog-hide class="text-sm inline gray"><?= __('app.cancel'); ?></span>
    </div>
  </div>
<?php endif; ?>