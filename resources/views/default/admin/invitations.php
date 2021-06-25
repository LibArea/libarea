<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?></span>
                </h1>

                <div class="telo invitations">
                    <?php if (!empty($invitations)) { ?>
                  
                        <?php foreach ($invitations as $key => $inv) { ?>  

                            <a href="/u/<?= $inv['uid']['login']; ?>"><?= $inv['uid']['login']; ?></a> <sup>id<?= $inv['uid']['id']; ?></sup>
                            => 
                            <a href="/u/<?= $inv['login']; ?>"><?= $inv['login']; ?></a> <sup>id<?= $inv['active_uid']; ?></sup> 
                            
                            <span class="date"><small> - <?= $inv['active_time']; ?></small></span>
                             
                            <br>

                        <?php } ?>
                        
                        <div class="pagination">
                      
                        </div>
                        
                    <?php } else { ?>
                        <div class="no-content"><?= lang('no-comment'); ?>...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>