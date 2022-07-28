<?php if (UserData::getUserTl() >= config('trust-levels.tl_add_report')) : ?>
  <script nonce="<?= $_SERVER['nonce']; ?>">
    document.querySelectorAll(".msg-flag")
      .forEach(el => el.addEventListener("click", function(e) {
        let post_id = el.dataset.post_id;
        let content_id = el.dataset.content_id;
        let type = el.dataset.type;
        fetch("/flag/repost", {
            method: "POST",
            body: "type=" + type + "&post_id=" + post_id + "&content_id=" + content_id,
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
      }));
  </script>
<?php endif; ?>