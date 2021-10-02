<aside>
  <div class="size-15 border-box-1 bg-white pt15 pr15 pb5 pl15">
    <h3 class="mt0">
      <?= $data['space']['space_short_text']; ?>
    </h3>
    <div class="flex flex-row flex-auto mt15 mb10">
      <div class="flex-auto">
        <p class="bl-n m0"><a href="<?= getUrlByName('user', ['login' => $data['space']['user_login']]); ?>">
            <?= $data['space']['user_login']; ?>
          </a></p>
        <p class="bl-t m0 size-15 lowercase gray-light"><?= lang('created by'); ?></p>
      </div>
      <div class="flex-auto">
        <?php if ($data['space']['space_id'] != 1) { ?>
          <p class="bl-n m0"><?= $data['space']['space_focus_count']; ?></p>
        <?php } else { ?>
          <p class="bl-n m0">***</p>
        <?php } ?>
        <p class="bl-t size-15 lowercase m0 gray-light"><?= lang('reads'); ?></p>
      </div>
    </div>
    <hr>
    <div class="gray-light">
      <i class="bi bi-calendar-week mr5 middle"></i>
      <span class="middle"><?= $data['space']['space_date']; ?></span>
    </div>
    <?php if (!$uid['user_id']) { ?>
      <div class="white mt15 mb10 center">
        <a class="mt15 button block mb15 white" href="<?= getUrlByName('login'); ?>">
          <i class="bi bi-pencil size-15"></i>
          <?= lang('create Post'); ?>
        </a>
      </div>
    <?php } else { ?>
      <div class="mt15 mb15 center">
        <?php if ($data['space']['space_user_id'] == $uid['user_id']) { ?>
          <a class="mt15 button block mb15 white" href="/post/add/space/<?= $data['space']['space_id']; ?>">
            <?= lang('create Post'); ?>
          </a>
        <?php } else { ?>
          <?php if ($data['signed']) { ?>
            <?php if ($data['space']['space_permit_users'] == 1) { ?>
              <?php if ($uid['user_trust_level'] == 5 || $space['space_user_id'] == $uid['user_id']) { ?>
                <a class="mt15 button block mb15 white" href="/post/add/space/<?= $data['space']['space_id']; ?>">
                  <?= lang('create Post'); ?>
                </a>
              <?php } else { ?>
                <div class="p5 pr15 pb5 pl15 text-center size-15 bg-gray-200 gray">
                  <?= lang('the owner restricted the publication'); ?>
                </div>
              <?php } ?>
            <?php } else { ?>
              <a class="mt15 button block mb15 white" href="/post/add/space/<?= $data['space']['space_id']; ?>">
                <?= lang('create Post'); ?>
              </a>
            <?php } ?>
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
  <div class="pt5 pr15 pb5 pl15 border-box-1 mt15 bg-white">
    <?= $data['space']['space_text']; ?>
  </div>
</aside>