<div>
  <?php if ($data['cover_art'] != 'cover_art.jpeg') : ?>
    <img class="block br-gray max-w-full mb15" src="<?= Img::cover($data['cover_art'], 'user'); ?>">
    <a class="right text-sm" href="<?= url('delete.user.cover', ['id' => $container->user()->id()]); ?>">
      <?= __('app.remove'); ?>
    </a>
  <?php else : ?>
    <div class="br-gray text-sm gray mb15 mt20">
      <?= __('app.no_cover'); ?>...
    </div>
  <?php endif; ?>

  <input type="file" id="txtCaminhoImagemCover" accept="image/*">
</div>
<div class="prevImg">
  <img id="previsaoImagemCover" style="display:none;">
</div>
<button id="btnUploadCover" class="btn btn-primary" style="display:none;"><?= __('app.download'); ?></button>

<script nonce="<?= config('main', 'nonce'); ?>">
  let cropperAva;

  const txtCaminhoImagemCover = document.getElementById('txtCaminhoImagemCover');
  const previsaoImagemCover = document.getElementById('previsaoImagemCover');
  const btnUploadCover = document.getElementById('btnUploadCover');

  txtCaminhoImagemCover.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        previsaoImagemCover.src = reader.result;
        previsaoImagemCover.style.display = 'block';
        if (cropperAva) {
          cropperAva.destroy();
        }
        cropperAva = new Cropper(previsaoImagemCover, {
          aspectRatio: 30 / 8,
          viewMode: 2,

        });

        btnUploadCover.style.display = 'inline';
      };
      reader.readAsDataURL(file);
    }
  });

  btnUploadCover.addEventListener('click', () => {
    if (cropperAva) {
      cropperAva.getCroppedCanvas().toBlob((blob) => {
        const formData = new FormData();

        formData.append('cover', blob, 'tmp_name.php');

        fetch('<?= url('setting.edit.avatar', method: 'post'); ?>', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '<?= csrf_token(); ?>'
            },
            body: formData
          })
          .then((response) => {
            location.reload();
          });
      });
    }
  });
</script>