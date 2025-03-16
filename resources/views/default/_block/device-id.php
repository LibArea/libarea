<?php if (\App\Controllers\DeviceController::index()) : ?>
  <script src="/assets/js/device/client.base.min.js"></script>
  <script nonce="<?= config('main', 'nonce'); ?>">
	const client = new ClientJS();
	const id = client.getFingerprint();
    fetch("/device", {
      method: "POST",
      body: "id=" + id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        return;
      }).then((text) => {
        //...
      });
  </script>
<?php endif; ?>