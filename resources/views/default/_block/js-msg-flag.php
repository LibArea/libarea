<?php if ($uid > 0) { ?>
  <script nonce="<?= $_SERVER['nonce']; ?>">
    document.querySelectorAll(".msg-flag")
      .forEach(el => el.addEventListener("click", function(e) {
        let post_id = el.dataset.post_id;
        let content_id = el.dataset.content_id;
        let type = el.dataset.type;
        Notiflix.Confirm.show(
          '<?= Translate::get('report'); ?>',
          '<?= Translate::get('breaking.rules'); ?>?',
          '<?= Translate::get('yes'); ?>',
          '<?= Translate::get('no'); ?>',
          function okCb() {
            fetch("/flag/repost", {
                method: "POST",
                body: "type=" + type + "&post_id=" + post_id + "&content_id=" + content_id,
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                }
              })
              .then(
                response => {
                  return;
                }
              ).then(
                text => {}
              )
          },
          function cancelCb() {
            // alert('...');
          }, {
            // option;  
          },
        );
      }));
  </script>
<?php } ?>