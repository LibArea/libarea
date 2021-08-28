<aside>
  <div class="size-15 white-box">
    <div class="pt15 pr15 pb5 pl15">
      <div class="mt15">
        <?= $data['space']['space_short_text']; ?>
      </div>
      <div class="flex w-100 mt15 mb10">
        <div class="_bl">
          <p class="bl-n m0"><a href="/u/<?= $data['space']['user_login']; ?>">
              <?= $data['space']['user_login']; ?>
            </a></p>
          <p class="bl-t m0 size-15 lowercase gray-light"><?= lang('Created by'); ?></p>
        </div>
        <div class="_bl">
          <?php if ($data['space']['space_id'] != 1) { ?>
            <p class="bl-n m0"><?= $data['space']['space_focus_count']; ?></p>
          <?php } else { ?>
            <p class="bl-n m0">***</p>
          <?php } ?>
          <p class="bl-t size-15 lowercase m0 gray-light"><?= lang('Reads'); ?></p>
        </div>
      </div>
      <hr>
      <div class="gray-light">
        <i class="icon-calendar middle"></i>
        <span class="middle"><?= $data['space']['space_date']; ?></span>
      </div>
      <?php if (!$uid['user_id']) { ?>
        <div class="white mt15 mb10 center">
          <a class="mt15 button block mb15 white" href="/login">
            <i class="icon-pencil size-15"></i>
            <?= lang('Create Post'); ?>
          </a>
        </div>
      <?php } else { ?>
        <div class="mt15 mb15 white center">
          <?php if ($data['space']['space_user_id'] == $uid['user_id']) { ?>
            <a class="mt15 button block mb15 white" href="/post/add/space/<?= $data['space']['space_id']; ?>">
              <?= lang('Create Post'); ?>
            </a>
          <?php } else { ?>
            <?php if ($signed) { ?>
              <?php if ($data['space']['space_permit_users'] == 1) { ?>
                <?php if ($uid['user_trust_level'] == 5 || $space['space_user_id'] == $uid['user_id']) { ?>
                  <a class="mt15 button block mb15 white" href="/post/add/space/<?= $data['space']['space_id']; ?>">
                    <?= lang('Create Post'); ?>
                  </a>
                <?php } else { ?>
                  <div class="p5 pr15 pb5 pl15 text-center size-15 bg-gray-200 gray">
                    <?= lang('The owner restricted the publication'); ?>
                  </div>
                <?php } ?>
              <?php } else { ?>
                <a class="mt15 button block mb15 white" href="/post/add/space/<?= $data['space']['space_id']; ?>">
                  <?= lang('Create Post'); ?>
                </a>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
  <div class="pt5 pr15 pb5 pl15 white-box">
    <?= $data['space']['space_text']; ?>
  </div>
</aside>