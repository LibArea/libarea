<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb0 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Favorites'));
      $pages = array(
        array('id' => 'favorites', 'url' => '/u/' . $uid['user_login'] . '/favorite', 'content' => lang('Favorites')),
        array('id' => 'subscribed', 'url' => '/u/' . $uid['user_login'] . '/subscribed', 'content' => lang('Subscribed')),
      );
      echo returnBlock('tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
      ?>
    </div>
    <?= returnBlock('/post', ['data' => $data, 'uid' => $uid]); ?>
  </main>
  <?= aside('lang', ['lang' => lang('info-preferences')]); ?>
</div>