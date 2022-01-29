<?php $topic = $data['facet']; ?>
<main class="col-span-9 mb-col-12">
  <?php if ($topic['facet_is_deleted'] == 0) { ?>
    <div class="bg-white flex flex-row items-center justify-between br-rd5 br-box-gray box-shadow-all mb15 p15">
      <div class="mb-none">
        <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w94 br-box-gray mt5'); ?>
      </div>
      <div class="ml15 mb-ml-0 flex-auto">
        <h1 class="mb0 mt10 text-2xl">
          <?= $topic['facet_seo_title']; ?>
          <?php if (UserData::checkAdmin() || $topic['facet_user_id'] == $user['id']) { ?>
            <a class="right gray-600" href="<?= getUrlByName('topic.edit', ['id' => $topic['facet_id']]); ?>">
              <i class="bi bi-pencil"></i>
            </a>
          <?php } ?>
        </h1>
        <div class="text-sm gray-400"><?= $topic['facet_short_description']; ?></div>

        <div class="mt15 right">
          <?= Tpl::import('/_block/facet/signed', [
            'user'            => $user,
            'topic'          => $topic,
            'topic_signed'   => is_array($data['facet_signed']),
          ]); ?>
        </div>

        <?= Tpl::import('/_block/facet/focus-users', [
          'user'               => $user,
          'topic_focus_count' => $topic['facet_focus_count'],
          'focus_users'       => $data['focus_users'] ?? '',
        ]); ?>

      </div>
    </div>

    <div class="bg-white flex flex-row items-center justify-between p15 mb5">
      <p class="m0 text-xl mb-none"><?= Translate::get('feed'); ?></p>
      <ul class="flex flex-row list-none m0 p0 center">

        <?= tabs_nav(
          'nav',
          $data['sheet'],
          $user,
          $pages = [
            [
              'id'      => 'facet.feed',
              'url'     => getUrlByName('topic', ['slug' => $topic['facet_slug']]),
              'title'   => Translate::get('feed'),
              'icon'    => 'bi bi-sort-down'
            ],
            [
              'id'      => 'facet.recommend',
              'url'     => getUrlByName('topic', ['slug' => $topic['facet_slug']]) . '/recommend',
              'title'   => Translate::get('recommended'),
              'icon'    => 'bi bi-lightning'
            ],
            [
              'id'      => 'info',
              'url'     => getUrlByName('topic.info', ['slug' => $topic['facet_slug']]),
              'title'   => Translate::get('info'),
              'icon'    => 'bi bi-info-lg'
            ],
          ]
        ); ?>

      </ul>
    </div>

    <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('topic', ['slug' => $topic['facet_slug']])); ?>


  <?php } else { ?>
    <div class="center col-span-10">
      <i class="bi bi-x-octagon text-8xl"></i>
      <div class="mt5 gray"><?= Translate::get('remote'); ?></div>
    </div>
  <?php } ?>
</main>
<aside class="col-span-3 relative mb-none">
  <?php if ($topic['facet_is_deleted'] == 0) { ?>
    <div class="bg-white flex justify-center br-rd5 mb15 br-box-gray box-shadow-all p15">
      <div class="mr15 center box-number">
        <div class="uppercase mb5 text-sm gray"><?= Translate::get('posts'); ?></div>
        <?= $topic['facet_count']; ?>
      </div>
      <div class="ml15 center box-number">
        <div class="uppercase mb5 text-sm gray"><?= Translate::get('reads'); ?></div>
        <div class="focus-user sky-500">
          <?= $topic['facet_focus_count']; ?>
        </div>
      </div>
    </div>

    <?php if (!empty($data['pages'])) { ?>
      <div class="sticky top0 top70">
        <div class="br-box-gray box-shadow-all mt15 p15 mb15 br-rd5 bg-white text-sm">
          <div class="uppercase gray mt5 mb5"> <?= Translate::get('pages'); ?></div>
          <?php foreach ($data['pages'] as $ind => $row) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="">
              <?= $row['post_title']; ?>
            </a>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

    <?= Tpl::import('/_block/sidebar/topic', ['data' => $data, 'user' => $user]); ?>
    <?php if (!empty($data['writers'])) { ?>
      <div class="sticky top0 top20">
        <div class="br-box-gray box-shadow-all mt15 p15 mb15 br-rd5 bg-white text-sm">
          <div class="uppercase gray mt5 mb5"> <?= Translate::get('writers'); ?></div>
          <?php foreach ($data['writers'] as $ind => $row) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="/@<?= $row['login']; ?>">
              <?= user_avatar_img($row['avatar'], 'max', $row['login'], 'w30 h30 mr5 br-rd-50'); ?>
              <span class="ml5"><?= $row['login']; ?> (<?= $row['hits_count']; ?>) </span>
            </a>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

  <?php } ?>
</aside>
<?= Tpl::import('/footer'); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.querySelectorAll(".focus-user")
    .forEach(el => el.addEventListener("click", function(e) {
      fetch('/topic/<?= $topic['facet_slug']; ?>/followers/<?= $topic['facet_id']; ?>').
      then(response => response.text()).
      then(function(data) {
        Swal.fire({
          title: '<?= Translate::get('reads'); ?>',
          showConfirmButton: false,
          html: data
        });
      });
    }));
</script>