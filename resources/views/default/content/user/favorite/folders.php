<main>
  <?= insert('/content/user/favorite/nav', ['data' => $data]); ?>
  <div class="box">
    <h4 class="uppercase-box"><?= __('app.folders_s'); ?>: <?= $data['count']; ?></h4>
    <?= insert('/_block/form/select/folders', ['data' => $data]); ?>
  </div>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('help.favorite_info'); ?>...
  </div>
</aside>

<script src="/assets/js/tag/tagify.min.js"></script>
<link rel="stylesheet" href="/assets/js/tag/tagify.css" type="text/css">