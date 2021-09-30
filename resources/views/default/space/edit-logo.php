<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <?= breadcrumb('/', lang('home'), '/s/' . $data['space']['space_slug'], $data['space']['space_name'], lang('logo') . ' - ' . $data['space']['space_slug']); ?>

  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
    <?= includeTemplate('/_block/space-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <div class="box create setting space">
    <form action="/space/logo/edit" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?= spase_logo_img($data['space']['space_img'], 'max', $data['space']['space_name'], 'ava'); ?>
      <div class="box-form-img">
        <div class="boxline">
          <div class="input-images"></div>
        </div>
      </div>
      <div class="clear mb15">
        <div class="gray size-14 mb15">
          <?= lang('recommended size'); ?>: 120x120px (jpg, jpeg, png)
        </div>
        <input type="hidden" name="space_id" id="space_id" value="<?= $data['space']['space_id']; ?>">
        <input type="submit" class="button white br-rd-5" name="submit" value="<?= lang('edit'); ?>" />
      </div>
      <?php if ($data['space']['space_cover_art'] != 'space_cover_no.jpeg') { ?>
        <img class="cover" width="100%" src="/uploads/spaces/cover/<?= $data['space']['space_cover_art']; ?>">
        <a class="right" href="/space/<?= $data['space']['space_slug']; ?>/delete/cover">
          <?= lang('remove'); ?>
        </a>
      <?php } else { ?>
        <div class="gray size-14">
          <?= lang('no-cover'); ?>...
        </div>
      <?php } ?>
      <div class="box setting avatar">
        <div class="box-form-img">
          <div class="boxline">
            <div class="input-images-cover"></div>
          </div>
        </div>
        <div class="boxline gray size-14">
          <?= lang('recommended size'); ?>: 1920x300px (jpg, jpeg, png)
        </div>
      </div>
      <div class="boxline">
        <input type="hidden" name="space_id" id="space_id" value="<?= $data['space']['space_id']; ?>">
        <input type="submit" class="button white br-rd-5" name="submit" value="<?= lang('edit'); ?>" />
      </div>
    </form>
  </div>
</main>
<aside class="col-span-3 no-mob">
  <div class="bg-white br-rd-5 border-box-1 p15">
    <?= lang('info-space-logo'); ?>
  </div>
</aside>