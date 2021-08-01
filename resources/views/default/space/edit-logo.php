<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/', lang('Home'), '/s/' . $space['space_slug'], $space['space_name'], $data['h1']); ?>

                <ul class="nav-tabs">
                    <li>
                        <a href="/space/edit/<?= $space['space_id']; ?>">
                            <span><?= lang('Edit'); ?></span>
                        </a>
                    </li>
                    <li class="active">
                        <span><?= lang('Logo'); ?> / <?= lang('Cover art'); ?></span>
                    </li>
                </ul>

                <div class="telo space">
                    <div class="box create">
                        <form action="/space/logo/edit" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="box setting space">
                                <?= spase_logo_img($space['space_img'], 'max', $space['space_name'], 'ava'); ?>
                                <div class="box-form-img">
                                    <div class="boxline">
                                        <div class="input-images"></div>
                                    </div>
                                </div>
                                <div class="clear">
                                    <p><?= lang('Recommended size'); ?>: 120x120px (jpg, jpeg, png)</p>
                                    <input type="hidden" name="space_id" id="space_id" value="<?= $space['space_id']; ?>">
                                    <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
                                    <br><br>
                                </div>
                            </div>
                            <?php if ($space['space_cover_art'] != 'space_cover_no.jpeg') { ?>
                                <img class="cover" src="/uploads/spaces/cover/<?= $space['space_cover_art']; ?>">
                                <a class="right" href="/space/<?= $space['space_slug']; ?>/delete/cover">
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
                                <div class="clear">
                                    <p>
                                        <?= lang('Recommended size'); ?>: 1920x300px (jpg, jpeg, png)
                                    </p>
                                </div>
                            </div>
                            <div class="clear">
                                <input type="hidden" name="space_id" id="space_id" value="<?= $space['space_id']; ?>">
                                <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('info_space_logo'); ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>