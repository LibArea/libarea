<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb0 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Favorites'));
      $pages = array(
        array('id' => 'favorites', 'url' => '/u/' . $uid['user_login'] . '/favorite', 'content' => lang('Favorites')),
        array('id' => 'subscribed', 'url' => '/u/' . $uid['user_login'] . '/subscribed', 'content' => lang('Subscribed')),
      );
      echo tabs_nav($pages, $data['sheet'], $uid);
      ?>
    </div>
    <?php include TEMPLATE_DIR . '/_block/post.php'; ?>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info_preferences'); ?>...
    </div>
  </aside>
</div>