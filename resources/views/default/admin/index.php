<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<main class="admin">
 
    <h1><?= $data['h1']?></h1>
    
    <div class="t-table">
        <div class="t-th">
            <span class="t-td center">N</span>
            <span class="t-td"><?= lang('Information'); ?></span>
            <span class="t-td">E-mail</span>
            <span class="t-td"><?= lang('Sign up'); ?></span>
            <span class="t-td">IP <?= lang('registrations'); ?></span>
            <span class="t-td center"><?= lang('Information'); ?></span>
            <span class="t-td center">Ban</span>
            <span class="t-td center"><?= lang('Action'); ?></span>
        </div>
        <?php foreach($alluser as $user) {  ?>
            <div class="t-tr">
                <span class="t-td w-30 center">
                    <?= $user['id']; ?>
                </span>
                <span class="t-td">
                    <img class="ava" src="/uploads/users/avatars/small/<?= $user['avatar']; ?>">
                    <a href="/u/<?= $user['login']; ?>"><?= $user['login']; ?></a>
                    <?php if($user['name']) { ?>
                        (<?= $user['name']; ?>) 
                    <?php } ?>
                    <sup class="red">TL:<?= $user['trust_level']; ?></sup>
                    <?php if($user['invitation_id'] !=0) { ?><sup>+ inv. id<?= $user['invitation_id']; ?></sup><?php } ?> <br>
                </span>
                <span class="t-td">
                     <span class="date"><?= $user['email']; ?></span>
                </span>
                <span class="t-td">
                     <?= $user['created_at']; ?> 
                </span>
                <span class="t-td">
                     <?= $user['reg_ip']; ?>  <?php if($user['replayIp'] > 1) { ?>
                       <sup class="red">(<?= $user['replayIp']; ?>)</sup>
                     <?php } ?>
                </span>
                <span class="t-td center">
                    <?php if(!empty($user['logs']['logs_ip_address'])) { ?>
                        <?= $user['logs']['logs_ip_address']; ?> <br> 
                        <small><?= $user['logs']['logs_date']; ?></small> 
                    <?php } else { ?>
                        <small class="red"><?= lang('Not activated'); ?> e-mail</small>
                    <?php } ?>
                </span>   
                <span class="t-td center"> 
                    <?php if($user['trust_level'] != 5) { ?>                 
                        <?php if($user['isBan']) { ?>
                            <span class="user-ban" data-id="<?= $user['id']; ?>">
                                <span class="red"><?= lang('unban'); ?></span>
                            <span>
                        <?php } else { ?>
                            <span class="user-ban" data-id="<?= $user['id']; ?>"><?= lang('ban it'); ?></span>
                        <?php } ?>
                    <?php } else { ?> 
                        ---
                    <?php } ?> 
                </span>
                <span class="t-td center">
                    <?php if($user['trust_level'] != 5) { ?> 
                        <a title="<?= lang('Edit'); ?>" href="/admin/user/<?= $user['id']; ?>/edit">
                            <i class="icon pencil"></i>
                        </a>
                    <?php } else { ?> 
                        ---
                    <?php } ?>                     
                </span>
            </div>
        <?php } ?>
    </div>
</main> 
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?>