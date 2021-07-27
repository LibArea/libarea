<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="inner-padding">
        <?= breadcrumb('/admin', lang('Admin'), '/admin/users',lang('Users'), $data['meta_title']); ?>
        
        <ul class="nav-tabs">
            <?php if($data['sheet'] == 'userall') { ?>
                <li class="active">
                    <span><?= lang('All'); ?></span>
                </li>
                <li>
                    <a href="/admin/users/ban">
                        <span><?= lang('Banned'); ?></span>
                    </a>
                </li>
            <?php } elseif($data['sheet'] == 'banuser') { ?>
                <li>
                    <a href="/admin/users">
                        <span><?= lang('All'); ?></span>
                    </a>
                </li>
                <li class="active">
                    <span><?= lang('Banned'); ?></span>
                </li>
            <?php } ?>  
        </ul>

        <div class="t-table">
          <div class="t-th">
            <span class="t-td center">N</span>
            <span class="t-td"><?= lang('Avatar'); ?></span>
            <span class="t-td"><?= lang('Information'); ?></span>
            <span class="t-td center">Ban</span>
            <span class="t-td">IP <?= lang('registrations'); ?></span>
            <span class="t-td"><?= lang('Last'); ?></span>
            <span class="t-td center"><?= lang('Action'); ?></span>
          </div>
          <?php if($alluser) {  ?>
              <?php foreach ($alluser as $user) {  ?>
              
                <div class="t-tr">
                  <span class="t-td width-30 center">
                    <?= $user['id']; ?>
                  </span>
                  <span class="t-td width-30 center">
                    <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'ava-64'); ?>
                  </span>
                  <span class="t-td">
                    <a href="/u/<?= $user['login']; ?>"><?= $user['login']; ?></a>
                    <?php if($user['name']) { ?>
                      (<?= $user['name']; ?>) 
                    <?php } ?>
                    <sup class="red">TL:<?= $user['trust_level']; ?></sup>
                    <?php if($user['invitation_id'] !=0) { ?><sup>+ inv. id<?= $user['invitation_id']; ?></sup><?php } ?>
                    <div class="small">
                      <?= $user['email']; ?>
                    </div>  
                    <?php if($user['limiting_mode'] == 1) { ?>
                          <div class="red"><?= lang('Dumb mode'); ?></div>
                    <?php } ?> 

                    <?php if (!empty($user['isBan']['banlist_int_num'])) { ?>
                          bans: <?= $user['isBan']['banlist_int_num']; ?>
                    <?php } ?>
                  </span>
                  <span class="t-td center"> 
                    <?php if($user['trust_level'] != 5) { ?>         
                      <?php if($user['isBan']) { ?>
                        <span class="user-ban" data-id="<?= $user['id']; ?>">
                          <span class="red"><?= lang('Unban'); ?></span>
                        </span>
                      <?php } else { ?>
                        <span class="user-ban" data-id="<?= $user['id']; ?>"><?= lang('Ban it'); ?></span>
                      <?php } ?>
                    <?php } else { ?> 
                      ---
                    <?php } ?> 
                  </span>
                  <span class="t-td">
                     <?= $user['reg_ip']; ?>  
                     <?php if($user['replayIp'] > 1) { ?>
                       <sup class="red">(<?= $user['replayIp']; ?>)</sup>
                     <?php } ?> <br>
                     <small><?= $user['created_at']; ?></small>
                  </span>
                  <span class="t-td">
                    <?php if(!empty($user['logs']['logs_ip_address'])) { ?>
                      <a href="/admin/logip/<?= $user['logs']['logs_ip_address']; ?>">
                        <?= $user['logs']['logs_ip_address']; ?>
                      </a> 
                      <br> 
                      <small><?= $user['logs']['logs_date']; ?></small> 
                    <?php } ?>
                    
                    <?php if($user['activated'] == 1) { ?>
                         <div class="small"><?= lang('Email activated'); ?></div>
                    <?php } else { ?>
                      <small class="red"><?= lang('Not activated'); ?> e-mail</small>
                    <?php } ?>
                  </span>   
     
                  <span class="t-td center">
                    <?php if($user['trust_level'] != 5) { ?> 
                      <a title="<?= lang('Edit'); ?>" href="/admin/user/<?= $user['id']; ?>/edit">
                        <i class="light-icon-edit middle"></i>
                      </a>
                    <?php } else { ?> 
                      ---
                    <?php } ?>           
                  </span>
                </div>
              <?php } ?>
          
          <?php } else { ?>
            <p class="no-content gray">
                <i class="light-icon-info-square green middle"></i>
                <span class="middle"><?= lang('No users'); ?>...</span>
            </p>
          <?php } ?>
        </div>
        
        <?= pagination($data['pNum'], $data['pagesCount'], null, '/admin/users'); ?>
      </div>
    </div>   
  </main> 
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>