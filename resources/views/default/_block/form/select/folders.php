<?php if (!empty($data['folders'])) { ?>
  <div class="mb15">
    <?php foreach ($data['folders'] as $tag) { ?>
      <a class="tags-xs" href="<?= url('favorites.folder.id', ['id' => $tag['id']]); ?>"><?= $tag['value']; ?></a>
      <sup class="del-folder gray-600 mr15" data-id="<?= $tag['id']; ?>" data-type="favorite">x</sup>
    <?php } ?>
  </div>
<?php } ?>

<form class="max-w780" action="<?= url('folder.content.create'); ?>" method="post" enctype="multipart/form-data">
  <?php csrf_field(); ?>
  <input name='cat-outside' class='tagify' placeholder='<?= __('add'); ?>...'>
  <fieldset>
    <?= Html::sumbit(__('add')); ?>
  </fieldset>
</form>
<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener("DOMContentLoaded", async () => {
    var input = document.querySelector('input[name=cat-outside]')
    var tagify = new Tagify(input, {
      <?php if ($data['count'] > 12) { ?> userInput: false,<?php } ?>
      dropdown: {
        skipInvalid: true, // <- не добавлять повтороно
        enabled: false,
      }
    })
  });
</script>