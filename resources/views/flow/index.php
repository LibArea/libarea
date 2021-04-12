<?php include TEMPLATE_DIR . '/header.php'; ?>
<?php include TEMPLATE_DIR . '/menu.php'; ?>
<meta http-equiv="Refresh" content="35" />
<link rel="stylesheet" href="/assets/css/flow.css">
<main class="info">
    <div class="left-ots flow">
        <h1><?= $data['h1']; ?></h1>
        
        <?php if (!empty($flows)) { ?> 
      
            <?php foreach ($flows as  $flow) { ?>
                
                <div class="comm-header">
                    <img class="ava" src="/uploads/avatar/small/<?= $flow['avatar']; ?>">
                    <span class="user"> 
                        <a href="/u/<?= $flow['login']; ?>"><?= $flow['login']; ?></a> 
                    </span> 
                    <span class="date">
                        <?= $flow['flow_pubdate']; ?> 
                    </span>
                </div>  
                <div>
                    <?= $flow['flow_content']; ?>
                </div>                
            <?php } ?> 
        
        <?php } else { ?> 
        
            <div class="no-content"><?= lang('no-post'); ?>...</div>
        
        <?php } ?> 
        
        <?php include TEMPLATE_DIR . '/flow/flow-form.php'; ?>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>