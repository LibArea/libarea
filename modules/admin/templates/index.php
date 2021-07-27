<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="inner-padding">
        <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>
        <h3><?= lang('All'); ?></h3> 
        <i class="light-icon-point middle"></i> <a rel="noreferrer" href="/answers"><?= lang('Answers-n'); ?></a><br>
        <i class="light-icon-point middle"></i> <a rel="noreferrer" href="/comments"><?= lang('Comments-n'); ?></a><br>
        <i class="light-icon-point middle"></i> <a rel="noreferrer" href="/web"><?= lang('Domains'); ?></a><br>
        
        <h3><?= lang('Help'); ?></h3> 
        <i class="light-icon-point middle"></i> <a rel="noreferrer" href="https://loriup.ru">LoriUP.ru</a></br>
        <i class="light-icon-point middle"></i> <a rel="noreferrer" href="https://phphleb.ru/">PHP Micro-Framework HLEB</a></br>
        </ul> 
        <hr>
        <div class="boxline">
            <label for="name">ОС:</label>
            <?= php_uname('s'); ?> <?php echo php_uname('r'); ?>
        </div>
        <div class="boxline">
            <label for="name">PHP:</label>
            <?= PHP_VERSION; ?>
        </div>
        <div class="boxline">
            <label for="name">MySQL:</label>
            <?= mysqli_get_client_info(); ?>
        </div>
        <div class="boxline">
            <label for="name"><?= lang('Freely'); ?>:</label>
            <?= $data['bytes']; ?>
        </div>
      </div>
    </div>   
  </main> 
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>