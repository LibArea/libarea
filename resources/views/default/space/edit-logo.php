<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/s/' . $data['space']['space_slug'], $data['space']['space_name'], lang('Logo') . ' - ' . $data['space']['space_slug']); ?>

      <ul class="nav-tabs list-none">
        <li>
          <a href="/space/edit/<?= $data['space']['space_id']; ?>">
            <span><?= lang('Edit'); ?></span>
          </a>
        </li>
        <li class="active">
          <span><?= lang('Logo'); ?> / <?= lang('Cover art'); ?></span>
        </li>
      </ul>

      <div class="box create">
        <form action="/space/logo/edit" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="box setting space">
            <?= spase_logo_img($data['space']['space_img'], 'max', $data['space']['space_name'], 'ava'); ?>
            <div class="box-form-img">
              <div class="boxline">
                <div class="input-images"></div>
              </div>
            </div>
            <div class="clear">
              <p><?= lang('Recommended size'); ?>: 120x120px (jpg, jpeg, png)</p>
              <input type="hidden" name="space_id" id="space_id" value="<?= $data['space']['space_id']; ?>">
              <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
              <br><br>
            </div>
          </div>
          <?php if ($data['space']['space_cover_art'] != 'space_cover_no.jpeg') { ?>
            <img class="cover" src="/uploads/spaces/cover/<?= $data['space']['space_cover_art']; ?>">
            <a class="right" href="/space/<?= $data['space']['space_slug']; ?>/delete/cover">
              <?= lang('Remove'); ?>
            </a>
          <?php } else { ?>
            <?= lang('no-cover'); ?>...
            <br><br>
          <?php } ?>
          <div class="box setting avatar">
            <div class="box-form-img">
              <div class="boxline">
                <div class="input-images-cover"></div>
              </div>
            </div>
            <div class="boxline">
              <p>
                <?= lang('Recommended size'); ?>: 1920x300px (jpg, jpeg, png)
              </p>
            </div>
          </div>
          <div class="boxline">
            <input type="hidden" name="space_id" id="space_id" value="<?= $data['space']['space_id']; ?>">
            <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
          </div>
        </form>
      </div>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info_space_logo'); ?>
    </div>
  </aside>
</div>