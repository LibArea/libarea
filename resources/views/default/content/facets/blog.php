<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<?php $blog = $data['facet']; ?>

<main class="col-span-7 mb-col-12">
  <?php if ($blog['facet_is_deleted'] == 0) { ?>

    <div class="bg-white flex flex-row items-center justify-between br-rd5 br-box-gray mb15 p15">
      <div class="no-mob">
        <?= facet_logo_img($blog['facet_img'], 'max', $blog['facet_title'], 'w94 br-box-gray mt5'); ?>
      </div>
      <div class="ml15 mb-ml-0 flex-auto">
        <h1 class="mb0 mt10 size-24">
          <?= $blog['facet_seo_title']; ?>
          <?php if ($uid['user_trust_level'] == 5 || $blog['facet_user_id'] == $uid['user_id']) { ?>
            <a class="right gray-light" href="<?= getUrlByName('blog.edit', ['id' => $blog['facet_id']]); ?>">
              <i class="bi bi-pencil size-15"></i>
            </a>
          <?php } ?>
        </h1>
        <div class="size-14 gray-light-2"><?= $blog['facet_short_description']; ?></div>

        <div class="mt15 right">
          <?= includeTemplate('/_block/facet/signed', [
            'user_id'        => $uid['user_id'],
            'topic'          => $blog,
            'topic_signed'   => is_array($data['facet_signed']),
          ]); ?>
        </div>

        <?= includeTemplate('/_block/facet/focus-users', [
          'topic_focus_count' => $blog['facet_focus_count'],
          'focus_users'       => $data['focus_users'] ?? '',
        ]); ?>
      </div>
    </div>

    <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('blog', ['slug' => $blog['facet_slug']])); ?>

  <?php } else { ?>
    <div class="center">
      <i class="bi bi-x-octagon size-110"></i>
      <div class="mt5 gray"><?= Translate::get('deleted'); ?></div>
    </div>
  <?php } ?>
</main>
<aside class="col-span-3 relative no-mob">
  <?php if ($blog['facet_is_deleted'] == 0) { ?>
    <div class="br-box-gray p15 mb15 br-rd5 bg-white size-14">
      <div class="uppercase gray mb5"> <?= Translate::get('created by'); ?></div>
      <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('user', ['login' => $data['user']['user_login']]); ?>">
        <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'w30 mr5 br-rd-50'); ?>
        <span class="ml5"><?= $data['user']['user_login']; ?></span>
      </a>
      <div class="gray-light-2 size-14 mt5">
        <i class="bi bi-calendar-week mr5 ml5 middle"></i>
        <span class="middle lowercase"><?= $blog['facet_add_date']; ?></span>
      </div>
    </div>

    <?php if ($data['info']) { ?>
      <div class="br-box-gray pt0 pr15 pb0 pl15 mb15 br-rd5 bg-white size-14 shown_post">
        <?= $data['info']; ?>
      </div>
    <?php } ?>

  <?php } ?>
</aside>
<?= includeTemplate('/_block/wide-footer'); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  $(document).on("click", ".focus-user", function() {
    fetch('/topic/<?= $blog['facet_slug']; ?>/followers/<?= $blog['facet_id']; ?>').
    then(response => response.text()).
    then(function(data) {
      Swal.fire({
        title: '<?= Translate::get('reads'); ?>',
        showConfirmButton: false,
        html: data
      });
    });
  });
</script>