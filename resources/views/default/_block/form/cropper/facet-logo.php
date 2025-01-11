<div class="flex gap items-center">
	<?= Img::image($data['facet_img'], $data['facet_title'], 'img-xl', 'logo', 'max'); ?>
	<input type="file" id="txtCaminhoImagemAva" accept="image/*">
</div>

<div class="prevImg">
	<img id="previsaoImagemAva" style="display:none;">
</div>
<button id="btnUploadAva" style="display:none;"><?= __('app.download'); ?></button>

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
					aspectRatio: 4 / 4,
					viewMode: 2,

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

				fetch('<?= url('edit.logo.facet', ['type' => $data['facet_type'], 'facet_id' => $data['facet_id']], method: 'post'); ?>', {
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