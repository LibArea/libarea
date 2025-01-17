<div class="flex gap items-center">
  <?= Img::avatar($data['avatar'], $data['login'], 'img-xl', 'max'); ?>
  <input type="file" id="txtCaminhoImagemAva" accept="image/*">
</div>

<div class="prevImg">
  <img id="previsaoImagemAva" style="display:none;">
</div>
<button id="btnUploadAva" class="btn btn-primary" style="display:none;"><?= __('app.download'); ?></button>

<script nonce="<?= config('main', 'nonce'); ?>">
  let cropper;

  const txtCaminhoImagemAva = document.getElementById('txtCaminhoImagemAva');
  const previsaoImagemAva = document.getElementById('previsaoImagemAva');
  const btnUploadAva = document.getElementById('btnUploadAva');

  txtCaminhoImagemAva.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        previsaoImagemAva.src = reader.result;
        previsaoImagemAva.style.display = 'block';
        if (cropper) {
          cropper.destroy();
        }
        cropper = new Cropper(previsaoImagemAva, {
          dragMode: 'move',
          autoCropArea: 0.85,
          restore: false,
          guides: false,
          center: false,
          highlight: false,
          cropBoxMovable: false,
          cropBoxResizable: false,
          toggleDragModeOnDblclick: false,
          data: {
            width: 165,
            height: 165,
          },
        });

        btnUploadAva.style.display = 'inline';
      };
      reader.readAsDataURL(file);
    }
  });

  btnUploadAva.addEventListener('click', () => {
    if (cropper) {
      cropper.getCroppedCanvas().toBlob((blob) => {
        const formData = new FormData();

        formData.append('images', blob, 'tmp_name.php');

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