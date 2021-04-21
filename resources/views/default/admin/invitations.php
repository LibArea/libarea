<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<main class="admin">
    <h1 class="top">
        <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?php echo $data['h1']; ?></span>
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
</main>
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?>  