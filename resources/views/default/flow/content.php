<?php if (!empty($flows)) { ?> 

    <?php foreach ($flows as  $flow) { ?>
 
        <div class="flow-telo
            <?php if($flow['flow_is_delete'] == 1) { ?> dell<?php } ?>
            <?php if($uid['trust_level'] ==5) { ?> block<?php } ?>">
        
            <?php if($uid['trust_level'] == 5) { ?>
                <span id="cm_dell" class="right">
                    <a data-flow="<?= $flow['flow_id']; ?>" class="delflow">
                        <small>
                            <?php if($flow['flow_is_delete'] == 1) { ?>
                                <?= lang('Recover'); ?> 
                            <?php } else { ?>
                                <span class="red"><?= lang('Remove'); ?></spam>
                            <?php } ?>
                        </small>
                    </a>
                </span>
            <?php } ?>
        
            <?php if($flow['flow_action_id'] == 5) { ?>
                <div class="comm-header">
                    <img class="avatar" src="/uploads/avatar/small/<?= $flow['avatar']; ?>">
                    <span class="user"> 
                        <a href="/u/<?= $flow['login']; ?>"><?= $flow['login']; ?></a> 
                    </span> 
                    <span class="date">
                        <?= $flow['flow_pubdate']; ?> 
                    </span>
                </div>  
                <div class="flow-telo">
                    <?= $flow['flow_content']; ?>
                </div>  
            <?php } elseif ($flow['flow_action_id'] == 4) { ?>
                <div class="flow-comment">
                    <icon name="bubbles"></icon>
                    <div class="box">
                        <img class="avatar" src="/uploads/avatar/small/<?= $flow['avatar']; ?>">
                        <span class="user"> 
                            <a href="/u/<?= $flow['login']; ?>"><?= $flow['login']; ?></a> 
                        </span> 
                        <span class="date">
                            <?= $flow['flow_pubdate']; ?> 
                        </span>
                          — <a href="<?= $flow['flow_url']; ?>"><?= $flow['flow_about']; ?>...</a>
                    </div>
                </div>
            <?php } else { ?>

            <?php } ?>
            
        </div>
    <?php } ?> 

<?php } else { ?> 

    <div class="no-content"><?= lang('no-post'); ?>...</div>

<?php } ?> 
        
