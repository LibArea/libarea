<div class="border-box-1 bg-white br-rd-5 mb15 pt5 pr15 pb10 pl15">
  <div class="mt10 mb5">
    <a class="flex" title="<?= $data['space_name']; ?>" href="<?= getUrlByName('space', ['slug' => $data['space_slug']]); ?>">
      <?= spase_logo_img($data['space_img'], 'max', $data['space_slug'], 'w24 mr5'); ?>
      <span class="ml5"><?= $data['space_name']; ?></span>
    </a>
  </div>
  <div class="gray-light-2 size-14"><?= $data['space_short_text']; ?></div>
</div>