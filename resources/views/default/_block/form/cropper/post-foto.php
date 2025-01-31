<div class="mb20">
	<?php if (!empty($post['post_content_img'])) : ?>
		<?= Img::image($post['post_content_img'], $post['post_title'], 'block br-gray max-w-full mb15', 'post', 'cover'); ?>
		<a class="img-remove text-sm" href="<?= url('delete.post.cover', ['id' => $post['post_id']]); ?>">
			<?= __('app.remove'); ?>
		</a>
	<?php else : ?>
		<div class="text-sm gray mb15">
			<?= __('app.no_cover'); ?>. <?= __('app.format_cover_post'); ?>.
		</div>

		<input type="file" id="txtCaminhoImagemCover" accept="image/*">

		<img id="inputImg" style="display:none;">
		<input type="hidden" id="UpImg" name="images" accept="image/*">
	<?php endif; ?>

	<div id="prev" class="prevImg">
		<img id="previsaoImagemCover" style="display:none;">
	</div>

	<div id="btnUploadCover" class="btn btn-primary" style="display:none;"><?= __('app.download'); ?></div>
</div>

<script nonce="<?= config('main', 'nonce'); ?>">
	let cropperAva;

	const txtCaminhoImagemCover = document.getElementById('txtCaminhoImagemCover');
	const previsaoImagemCover = document.getElementById('previsaoImagemCover');
	const btnUploadCover = document.getElementById('btnUploadCover');
	const prev = document.getElementById('prev');
	const inputImg = document.getElementById('inputImg');
	const UpImg = document.getElementById('UpImg');

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
					dragMode: 'move',
					autoCropArea: 0.65,
					restore: false,
					guides: false,
					center: false,
					highlight: false,
					cropBoxMovable: true,
					cropBoxResizable: false,
					toggleDragModeOnDblclick: false,
					data: {
						width: 1050,
						height: 500,
					},
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

				var croppedImageDataURL = cropperAva.getCroppedCanvas();

				prev.style.display = 'none';

				inputImg.style.display = 'block';

				inputImg.setAttribute("src", croppedImageDataURL.toDataURL("image/jpeg"));

				UpImg.setAttribute("value", croppedImageDataURL.toDataURL("image/jpeg"));
			});
		}
	});
</script>