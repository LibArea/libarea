<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    const ajaxSend = async (formData) => {
      const fetchResp = await fetch('<?= $url; ?>', {
        method: 'POST',
        body: formData
      });
      if (!fetchResp.ok) {
        throw new Error(`error url ${url}, status: ${fetchResp.status}`);
      }
      return await fetchResp.text();
    };

    const forms = document.querySelectorAll('<?= $id; ?>');
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        ajaxSend(formData)
          .then((response) => {
            let is_valid = JSON.parse(response);
            if (is_valid.error == 'error') {
              Notiflix.Notify.failure(is_valid.text);
              return;
            }
            if (is_valid.error == 'redirect') {
              Notiflix.Notify.failure(is_valid.text);
                setTimeout(function () {
                    window.location.replace('/');
                }, 2000);
                return;
            }
            Notiflix.Notify.success('<?= $success; ?>');
            setTimeout(function () {
               window.location.replace('<?= $redirect; ?>');
            }, 2000);
          })
          .catch((err) => console.error(err))
      });
    });
  });
</script>