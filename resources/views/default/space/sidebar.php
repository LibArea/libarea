<aside>
    <div class="size-15 white-box">
        <div class="pt15 pr15 pb5 pl15">
            <div class="mt15">
                <?= $space['space_short_text']; ?>
            </div>
            <div class="sb-space-stat">
                <div class="_bl">
                    <p class="bl-n"><a href="/u/<?= $space['user_login']; ?>">
                        <?= $space['user_login']; ?>
                    </a></p>
                    <p class="bl-t gray-light"><?= lang('Created by'); ?></p>
                </div>
                <div class="_bl">
                    <?php if ($space['space_id'] != 1) { ?>
                        <p class="bl-n"><?= $space['users']; ?></p>
                    <?php } else { ?>
                        <p class="bl-n">***</p>
                    <?php } ?>
                    <p class="bl-t gray-light"><?= lang('Reads'); ?></p>
                </div>
            </div>
            <hr>
            <div class="gray-light">
                <i class="icon-calendar middle"></i>
                <span class="middle"><?= $space['space_date']; ?></span>
            </div>
            <?php if (!$uid['user_id']) { ?>
                <div class="sb-add-space-post center">
                    <a class="mt15 mb20 white" href="/login">
                        <i class="icon-pencil size-15"></i>
                        <?= lang('Create Post'); ?>
                    </a>
                </div>
            <?php } else { ?>
                <div class="mt15 mb20 white center">
                    <?php if ($space['space_user_id'] == $uid['user_id']) { ?>
                        <a class="add-space-post" href="/post/add/space/<?= $space['space_id']; ?>">
                            <?= lang('Create Post'); ?>
                        </a>
                    <?php } else { ?>
                        <?php if ($signed) { ?>
                            <?php if ($space['space_permit_users'] == 1) { ?>
                                <?php if ($uid['user_trust_level'] == 5 || $space['space_user_id'] == $uid['user_id']) { ?>
                                    <a class="add-space-post" href="/post/add/space/<?= $space['space_id']; ?>">
                                        <?= lang('Create Post'); ?>
                                    </a>
                                <?php } else { ?>
                                    <span class="restricted"><?= lang('The owner restricted the publication'); ?></span>
                                <?php } ?>
                            <?php } else { ?>
                                <a class="add-space-post" href="/post/add/space/<?= $space['space_id']; ?>">
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
         <?= $space['space_text']; ?>
    </div>
</aside>
