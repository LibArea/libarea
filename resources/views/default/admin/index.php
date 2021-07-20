<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="inner-padding">
        <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>
         
         
        <h3><?= lang('All'); ?></h3> 
        <ul> 
            <li><a rel="noreferrer" href="/answers"><?= lang('Answers-n'); ?></a></li>
            <li><a rel="noreferrer" href="/comments"><?= lang('Comments-n'); ?></a></li>
            <li><a rel="noreferrer" href="/web"><?= lang('Domains'); ?></a></li>
        </ul> 

        <h3><?= lang('Help'); ?></h3> 
        <ul> 
            <li><a rel="noreferrer" href="https://loriup.ru">LoriUP.ru</a></li>
            <li><a rel="noreferrer" href="https://phphleb.ru/">PHP Micro-Framework HLEB</a></li>
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
  <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>