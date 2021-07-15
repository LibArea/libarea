<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?></span>
                </h1>

                <ul class="nav-tabs">
                  <?php if($data['sheet'] == 'all') { ?>
                    <li class="active">
                      <span><?= lang('New ones'); ?></span>
                    </li>
                    <li>
                      <a href="/admin/audit/approved">
                        <span><?= lang('Approved'); ?></span>
                      </a>
                    </li>
                  <?php } else { ?>
                    <li>  
                      <a href="/admin/audit">
                        <span><?= lang('New ones'); ?></span>
                      </a>
                    </li>
                    <li class="active">
                      <span><?= lang('Approved'); ?></span>
                    </li>
                  <?php } ?>    
                </ul>

                <div class="space">
                    <?php if (!empty($audits)) { ?>
                  
                        <div class="t-table">
                            <div class="t-th">
                                <span class="t-td center">Id</span>
                                <span class="t-td"><?= lang('Info'); ?></span>
                                <span class="t-td center"><?= lang('Action'); ?></span>
                                <span class="t-td center"><?= lang('Audit'); ?></span>
                            </div>

                            <?php foreach ($audits as $key => $audit) { ?>  
                                <div class="t-tr">
                                    <span class="t-td w-30 center">
                                        <?= $audit['audit_id']; ?>
                                    </span>  
                                    <span class="t-td">
                                        <?= lang('Type'); ?>: <b><?= $audit['audit_type']; ?></b>
                                        <?php if($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
                                             <span class="red"><?= lang('Deleted'); ?> </span>
                                        <?php } ?>
                                        <br>
                                        <?= $audit['content'][$audit['audit_type'] . '_content']; ?>
                                        <hr>
                                        <span class="small">
                                            <a href="/admin/user/<?= $audit['content']['answer_user_id']; ?>/edit">
                                                <?= lang('Author'); ?>
                                            </a> 
                                            (id: <?= $audit['content'][$audit['audit_type'] . '_user_id']; ?>)
                                            — <?= $audit['content'][$audit['audit_type'] . '_date']; ?>
                                        </span>
                                    </span>
                                    <span class="t-td w-30 center">
                                        <a data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" data-type="<?= $audit['audit_type']; ?>" class="type-action small">
                                            <?php if($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
                                                 <span class="red"><?= lang('Recover'); ?></span>
                                            <?php } else { ?>
                                                  <?= lang('Remove'); ?>
                                            <?php } ?>
                                        </a>
                                    </span>
                                    <span class="t-td w-30 center">
                                         
                                        <?php if($audit['audit_read_flag'] == 1) { ?>
                                            id: 
                                            <a href="/admin/user/<?= $audit['audit_user_id']; ?>/edit">
                                                <?= $audit['audit_user_id']; ?>
                                            </a>
                                        <?php } else { ?>
                                            <a data-status="<?= $audit['audit_type']; ?>" data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" class="audit-status small">
                                                <?= lang('To approve'); ?>
                                            </a>
                                        <?php } ?>
                                             
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                        
                    <?php } else { ?>
                        <div class="no-content"><?= lang('No'); ?>...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/audits'); ?>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>