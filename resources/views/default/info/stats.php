<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">
        <h1><?= $data['h1']; ?></h1>
        <div class="box wide">
            <label class="required"><?= lang('Users'); ?>:</label>
                <span class="d">
                    <?= $data['user_num']; ?>
                </span>
                <br>
            <label class="required"><?= lang('Posts'); ?>:</label>
                <span class="d">
                    <?= $data['post_num']; ?>
                </span>
                <br>
            <label class="required"><?= lang('Comments'); ?>:</label>
                <span class="d">
                    <?= $data['comm_num']; ?>
                </span>
                <br>
            <label class="required"><?= lang('Like'); ?>:</label>
                <span class="d lowercase">
                    <?= lang('Comments'); ?>: <?= $data['vote_comm_num']; ?>
                </span>
                <br>
                <label class="required"></label>
                <span class="d lowercase">
                   <?= lang('Posts'); ?>: <?= $data['vote_post_num']; ?>
                </span>
                <br>
        </div>
        <div class="v-ots"><br></div>
            <h3><?= lang('Activity'); ?></h3>
            <svg viewBox="0 0 500 100" class="chart">
              <polyline
                 fill="none"
                 stroke="#0074d9"
                 stroke-width="2"
                 points="
                <?php foreach ($data['flow_num'] as  $flow) { ?>
                    <?= $flow['date']; ?>, <?= $flow['0']; ?>
                <?php } ?>
              "/>
            </svg>
            <i><?= lang('Under development'); ?>...</i>
    </main>
    <?php include TEMPLATE_DIR . '/_block/info-page-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>