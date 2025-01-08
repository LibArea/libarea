<main>
  <?= insert('/content/user/setting/nav'); ?>
  <div class="box">
    <form class="max-w-md" action="<?= url('setting.edit.profile', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>
      <?= insert('/_block/form/setting', ['data' => $data]); ?>
    </form>
  </div>
</main>

<aside>
  <div class="box">
    <?= __('help.setting_info'); ?>
  </div>
</aside>

<div class="dialog-container" id="my-email" aria-hidden="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-description">
  <div class="dialog-overlay" data-a11y-dialog-hide></div>
  <div class="dialog-content" role="document">
    <button data-a11y-dialog-hide class="dialog-close" aria-label="Close this dialog window">
      &times;
    </button>

    <h2 class="uppercase-box"><?= __('app.edit'); ?> Email</h2>

    <fieldset>
      <input id="in_email" type="text" placeholder="<?= __('app.new'); ?> email" name="new_email">
    </fieldset>
    <button id="flag" class="btn btn-primary mr5" type="submit" name="button"><?= __('app.edit'); ?></button>
    <span data-a11y-dialog-hide class="text-sm inline gray"><?= __('app.cancel'); ?></span>
  </div>
</div>

<script nonce="<?= config('main', 'nonce'); ?>">
  document.addEventListener('DOMContentLoaded', () => {
    let dialogEmail = getById('my-email');
    let dialog = new A11yDialog(dialogEmail);

    dialogEmail.addEventListener('show', function(event) {

      let flag = document.getElementById("flag");
      flag.addEventListener('click', function(e) {

        let new_email = document.getElementById('in_email').value;

        dialog.hide();

        fetch("/new/email", {
            method: "POST",
            body: "email=" + new_email + "&_token=" + token,
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            }
          })
          .then((response) => {
            return response.json();
          }).then((text) => {

            if (text === 'errort') {
              var text = '<?= __('msg.email_correctness'); ?>';
              var color = 'red';
            } else if (text === 'repeat') {
              var text = '<?= __('msg.email_replay'); ?>';
              var color = 'red';
            } else {
              var text = '<?= __('msg.new_email'); ?>';
              var color = 'green';
            }

            Notice(text, 4500, {
              valign: 'bottom',
              align: 'center',
              styles: {
                backgroundColor: color,
                fontSize: '18px'
              }
            });

            reload();
          });
      });
    });

    function reload() {
      setTimeout(function() {
        location.reload();
      }, 1500)
    }

  });
</script>