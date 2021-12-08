<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<?php $topic = $data['facet']; ?>
<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-rd5 br-box-gray mb15 p15">
    <div class="no-mob">
      <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w94 br-box-gray mt5'); ?>
    </div>
    <div class="ml15 mb-ml-0 flex-auto">
      <h1 class="mb0 mt10 size-24">
        <?= $topic['facet_seo_title']; ?>
        <?php if ($uid['user_trust_level'] == 5 || $topic['facet_user_id'] == $uid['user_id']) { ?>
          <a class="right gray-light" href="<?= getUrlByName('topic.edit', ['id' => $topic['facet_id']]); ?>">
            <i class="bi bi-pencil size-15"></i>
          </a>
        <?php } ?>
      </h1>
      <div class="size-14 gray-light-2"><?= $topic['facet_short_description']; ?></div>

      <div class="mt15 right">
        <?= includeTemplate('/_block/facet/signed', [
          'user_id'        => $uid['user_id'],
          'topic'          => $topic,
          'topic_signed'   => is_array($data['facet_signed']),
        ]); ?>
      </div>

      <?= includeTemplate('/_block/facet/focus-users', [
        'topic_focus_count' => $topic['facet_focus_count'],
        'focus_users'       => $data['focus_users'] ?? '',
      ]); ?>

    </div>
  </div>

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 size-18 no-mob"><?= Translate::get('feed'); ?></p>
    <ul class="flex flex-row list-none m0 p0 center size-15">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        $pages = [
          [
            'id' => 'topic',
            'url' => getUrlByName('topic', ['slug' => $topic['facet_slug']]),
            'content' => Translate::get('feed'),
            'icon' => 'bi bi-sort-down'
          ],
          [
            'id' => 'recommend',
            'url' => getUrlByName('topic', ['slug' => $topic['facet_slug']]) . '/recommend',
            'content' => Translate::get('recommended'),
            'icon' => 'bi bi-lightning'
          ],
          [
            'id' => 'info',
            'url' => getUrlByName('topic.info', ['slug' => $topic['facet_slug']]),
            'content' => Translate::get('info'),
            'icon' => 'bi bi-info-lg'
          ],
        ]
      ); ?>

    </ul>
  </div>

  <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('topic', ['slug' => $topic['facet_slug']])); ?>

</main>
<aside class="col-span-3 relative no-mob">
  <div class="bg-white flex justify-center br-rd5 mb15 br-box-gray p15">
    <div class="mr15 center box-number">
      <div class="uppercase mb5 size-14 gray"><?= Translate::get('posts'); ?></div>
      <?= $topic['facet_count']; ?>
    </div>
    <div class="ml15 center box-number">
      <div class="uppercase mb5 size-14 gray"><?= Translate::get('reads'); ?></div>
      <div class="focus-user blue">
        <?= $topic['facet_focus_count']; ?>
      </div>
    </div>
  </div>
  <?= includeTemplate('/_block/sidebar/topic', ['data' => $data, 'uid' => $uid]); ?>
  <?php if (!empty($data['writers'])) { ?>
    <div class="sticky top0 top80">
      <div class="br-box-gray mt15 p15 mb15 br-rd5 bg-white size-14">
        <div class="uppercase gray mt5 mb5"> <?= Translate::get('writers'); ?></div>
        <?php foreach ($data['writers'] as $ind => $row) { ?>
          <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('user', ['login' => $row['user_login']]); ?>">
            <?= user_avatar_img($row['user_avatar'], 'max', $row['user_login'], 'w24 mr5 br-rd-50'); ?>
            <span class="ml5"><?= $row['user_login']; ?> (<?= $row['hits_count']; ?>) </span>
          </a>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
</aside>

<?= includeTemplate('/_block/wide-footer'); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.querySelectorAll(".focus-user")
  .forEach(el => el.addEventListener("click", function (e) {    
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