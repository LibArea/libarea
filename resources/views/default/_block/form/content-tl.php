<fieldset>
	<div class="form-label input-label"><label><?= __('app.for'); ?> TL</label></div>
	<div class="form-element">
	  <select name="content_tl">
		<?php for ($i = 0; $i <= $container->user()->tl(); $i++) {
		  if ($i == 5) {
			break;
		  }
		?>
		
		<?php  $data = (!empty($data)) ? $data : false; ?>
		<option <?php if ($data == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
		  
		<?php } ?>
	  </select>
		<div class="help"><?= __('app.view_post_tl'); ?></div>
	</div>
</fieldset>