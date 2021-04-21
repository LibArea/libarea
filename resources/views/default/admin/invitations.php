<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<div class="w-100">
    <h1 class="top"><?php echo $data['h1']; ?></h1>

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