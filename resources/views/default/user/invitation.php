<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['login'], lang('Profile'), $data['h1']); ?>

                <?php if ($uid['trust_level'] > 1) { ?>

                    <form method="post" action="/invitation/create">
                        <?php csrf_field(); ?>

                        <div class="boxline">
                            <input id="link" class="form-input" type="email" name="email">
                            <input class="button right" type="submit" name="submit" value="<?= lang('To create'); ?>">
                            <div class="box_h"><?= lang('Enter'); ?> e-mail</div>
                        </div>
                        <?= lang('Invitations left'); ?> <?= 5 - $uid['invitation_available']; ?>

                    </form>

                    <h3><?= lang('Invited guests'); ?></h3>

                    <?php if (!empty($result)) { ?>

                        <?php foreach ($result as $inv) { ?>
                            <?php if ($inv['active_status'] == 1) { ?>
                                <div class="comm-header">
                                    <?= user_avatar_img($inv['avatar'], 'small', $inv['login'], 'ava'); ?>
                                    <a href="<?= $inv['login']; ?>"><?= $inv['login']; ?></a>
                                    - <?= lang('registered'); ?>
                                </div>

                                <?php if ($uid['trust_level'] == 5) { ?>
                                    <?= lang('The link was used to'); ?>: <?= $inv['invitation_email']; ?> <br>
                                    <code>
                                        <?= Lori\Config::get(Lori\Config::PARAM_URL); ?>/register/invite/ <?= $inv['invitation_code']; ?>
                                    </code>
                                <?php } ?>

                                <small><?= lang('Link has been used'); ?></small>
                            <?php } else { ?>

                                <?= lang('For'); ?> (<?= $inv['invitation_email']; ?>) <?= lang('can send this link'); ?>: <br>

                                <code>
                                    <?= Lori\Config::get(Lori\Config::PARAM_URL); ?>/register/invite/ <?= $inv['invitation_code']; ?>
                                </code>

                            <?php } ?>

                            <br><br>
                        <?php } ?>

                    <?php } else { ?>
                        <?= lang('No invitations'); ?>
                    <?php } ?>

                <?php } else { ?>
                    <?= lang('limit_tl_invitation'); ?>.
                <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('Under development'); ?>...
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>