<div class="wrap width-100">
  <main class="white-box w-100 pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), null, null, lang('Welcome')); ?>
     
    <?= lang('Hi'); ?>, <?= $uid['user_login']; ?>!  
    <div class="mt10 max-width">
      <?= lang('welcome-info'); ?>
    </div>
  </main>
</div>