<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb0 pl15">
      <?= breadcrumb('/', lang('Home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('Profile'), lang('Favorites'));
      $pages = array(
        array('id' => 'favorites', 'url' => getUrlByName('favorites', ['login' => $uid['user_login']]), 'content' => lang('Favorites')),
        array('id' => 'subscribed', 'url' => getUrlByName('subscribed', ['login' => $uid['user_login']]), 'content' => lang('Subscribed')),
      );
      echo returnBlock('tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
      ?>
    </div>
    <?= returnBlock('/post', ['data' => $data, 'uid' => $uid]); ?>
  </main>
  <?= returnBlock('aside-lang', ['lang' => lang('info-preferences')]); ?>
</div>