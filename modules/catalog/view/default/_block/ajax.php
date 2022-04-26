<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    const ajaxSend = async (formData) => {
      const fetchResp = await fetch('<?= url($url); ?>', {
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
            window.location.replace('<?= url($redirect); ?>');
          })
          .catch((err) => console.error(err))
      });
    });
  });
</script>