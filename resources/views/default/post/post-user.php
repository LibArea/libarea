<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/', lang('Home'), '/u/' . Request::get('login'), lang('Profile'), $data['h1']); ?>
      </div>
    </div>

    <?php include TEMPLATE_DIR . '/_block/post.php'; ?>
  </main>
  <aside>
    <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
  </aside>
</div>