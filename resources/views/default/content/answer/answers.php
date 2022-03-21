<main>
  <div class="box-flex-white relative">
    <ul class="nav">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $user,
        $pages = [
          [
            'tl'    => 0,
            'id'    => $data['type'] . '.all',
            'url'   => '/answers',
            'title' => Translate::get('answers'),
            'icon'  => 'bi-sort-down'
          ],
          [
            'tl'    => UserData::REGISTERED_ADMIN,
            'id'    => $data['type'] . '.deleted',
            'url'   => getUrlByName('answers.deleted'),
            'title' => Translate::get('deleted'),
            'icon'  => 'bi-app'
          ],
        ]
      ); ?>

    </ul>
    <div class="trigger">
      <i class="bi-info-square gray-600"></i>
    </div>
    <div class="dropdown tooltip"><?= Translate::get($data['sheet'] . '.info'); ?></div>
  </div>

  <?php if (!empty($data['answers'])) { ?>
    <div class="box-white">
      <?= Tpl::import('/content/answer/answer', ['data' => $data, 'user' => $user]); ?>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>

  <?php } else { ?>
    <?= no_content(Translate::get('no.comments'), 'bi-info-lg'); ?>
  <?php } ?>
</main>
<aside>
  <div class="box-white text-sm sticky top-sm">
    <?=  Translate::get('answers-desc'); ?>
  </div>
</aside>