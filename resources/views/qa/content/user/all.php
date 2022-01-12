<main class="col-span-12 mb-col-12">
  <div class="bg-white box-shadow-all center justify-between br-box-gray br-rd5 p15 mb15">
    <h1 class="m0 text-xl font-normal"><?= Translate::get($data['sheet']); ?></h1>
    <span class="text-sm gray-500">
      <?= Translate::get($data['sheet'] . '.info'); ?>.
    </span>
  </div>

  <div class="bg-white flex flex-row items-center justify-between br-rd5 p15">
    <ul class="flex flex-row list-none m0 p0 center text-sm">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $uid,
        $pages = [
          [
            'id'    => $data['type'] . 's.all',
            'url'   => getUrlByName($data['type'] . 's.all'),
            'title' => Translate::get('all'),
            'icon'  => 'bi bi-app'
          ],
        ]
      );
      ?>

    </ul>
  </div>

  <div class="bg-white p20">
    <div class="max-width mr-auto w-100 grid grid-cols-6 gap-2 justify-between">
      <?php foreach ($data['users'] as $ind => $user) { ?>
        <div class="center inline pr10 pl10 mb20 mb-col-2">
          <a href="<?= getUrlByName('profile', ['login' => $user['user_login']]); ?>">
            <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'br-rd-50 w60 h60'); ?>
          </a>
          <a class="block black mt5" href="<?= getUrlByName('profile', ['login' => $user['user_login']]); ?>">
            <?= $user['user_login']; ?>
          </a>
          <?php if ($user['user_name']) { ?>
            <span class="gray text-sm"><?= $user['user_name']; ?></span>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/users'); ?>
</main>
</div>
<?= import('/_block/wide-footer', ['uid' => $uid]); ?>