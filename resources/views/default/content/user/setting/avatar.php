<main class="w-100">
  <?= insert('/content/user/setting/nav'); ?>
  <div class="box">
    <?= insert('/_block/form/cropper/user-avatar', ['data' => $data['user']]); ?>
    <br>
    <?= insert('/_block/form/cropper/user-cover', ['data' => $data['user']]); ?>
    <br>
    <div class="box-info">
      <?= __('help.avatar_info'); ?>
    </div>
  </div>
</main>

<script src="/assets/js/cropper/cropper.min.js"></script>
<link rel="stylesheet" href="/assets/js/cropper/cropper.min.css" type="text/css">