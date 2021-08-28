<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/', lang('Home'), '/info', lang('Info'), lang('Privacy Policy')); ?>
        <?= $data['content']; ?>
      </div>
    </div>
  </main>
  <aside>
    <?php include TEMPLATE_DIR . '/_block/info-page-menu.php'; ?>
  </aside>
</div>