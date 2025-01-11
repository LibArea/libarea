<div class="mb20">
	<div>
		<?php if (!empty($post['post_content_img'])) : ?>
			 <?= Img::image($post['post_content_img'], $post['post_title'], 'block br-gray max-w-full mb15', 'post', 'cover'); ?>
			  <a class="img-remove text-sm" href="<?= url('delete.post.cover', ['id' => $post['post_id']]); ?>">
				<?= __('app.remove'); ?>
			  </a>
		<?php else : ?>
			<div class="br-gray text-sm gray mb15">
				<?= __('app.no_cover'); ?>...
			</div>
		<?php endif; ?>
		<input type="file" id="txtCaminhoImagemCover" name="images" accept="image/*">

	</div>
	<div id="prev" class="prevImg">
		<img id="previsaoImagemCover" style="display:none;">
	</div>

	<div id="btnUploadCover" style="display:none;"><?= __('app.download'); ?></div>
</div>

<script nonce="<?= config('main', 'nonce'); ?>">
	let cropperAva;

	const txtCaminhoImagemCover = document.getElementById('txtCaminhoImagemCover');
	const previsaoImagemCover = document.getElementById('previsaoImagemCover');
	const btnUploadCover = document.getElementById('btnUploadCover');
	const prev = document.getElementById('prev');
	const inputImg = document.getElementById('inputImg');

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
					aspectRatio: 16 / 8,
					viewMode: 2,

				});

				btnUploadCover.style.display = 'inline';
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
				
				//prev.style.display = 'none';
				txtCaminhoImagemCover.value = blob;

				/* fetch('<?= url('setting.edit.avatar', method: 'post'); ?>', {
						method: 'POST',
						headers: {
							'X-CSRF-TOKEN': '<?= csrf_token(); ?>'
						},
						body: formData
					})
					.then((response) => {
						location.reload();
					}); */
			});
		}
	});

</script>