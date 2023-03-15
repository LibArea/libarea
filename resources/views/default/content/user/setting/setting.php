<main>
  <?= insert('/content/user/setting/nav'); ?>

  <form class="max-w780" action="<?= url('setting.change', ['type' => 'setting']); ?>" method="post">
    <?php csrf_field(); ?>
    <?= insert('/_block/form/setting', ['data' => $data]); ?>
  </form>
</main>

<aside>
  <div class="box bg-beige">
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

<script nonce="<?= $_SERVER['nonce']; ?>">
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
                
   
                if (text === 'error') {
                     Notice('<?= __('msg.email_correctness'); ?>', 3500, {
                        valign: 'bottom',
                        align: 'center',
                        styles: {
                          backgroundColor: 'red',
                          fontSize: '18px'
                        }
                      });
                } else {
                    
                  Notice('<?= __('msg.new_email'); ?>', 3500, {
                    valign: 'bottom',
                    align: 'center',
                    styles: {
                      backgroundColor: 'green',
                      fontSize: '18px'
                    }
                  }); 
                }  
           
            });
        });       
    })
  });
</script>  