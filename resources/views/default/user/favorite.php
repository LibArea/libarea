<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <?= breadcrumb('/', lang('home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('profile'), lang('favorites')); ?>
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'favorites', 'url' => getUrlByName('favorites', ['login' => $uid['user_login']]), 'content' => lang('favorites'), 'icon' => 'bi bi-bookmark'],
      ['id' => 'subscribed', 'url' => getUrlByName('subscribed', ['login' => $uid['user_login']]), 'content' => lang('subscribed'), 'icon' => 'bi bi-bookmark-plus'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <?php if (!empty($data['favorites'])) { ?>
    <?php foreach ($data['favorites'] as $content) { ?>
      <div class="bg-white br-rd-5 border-box-1 mt10 pt5 pr15 pb15 pl15">
        <div class="pt5 pr15 pb0 size-14 flex">
          <?= user_avatar_img($content['post']['user_avatar'], 'small', $content['post']['user_login'], 'w18 mr10'); ?>
          <?= $content['post']['user_login']; ?>
          <span class="ml10 gray">
            <?= $content['post']['post_date']; ?>
          </span>
        </div>
        <h3 class="size-24 mt0 mr15 mb0">
          <?php if ($content['favorite_type'] == 1) {  ?>
            <span id="favorite" class="add-favorite size-14 ml15 right" data-front="personal" data-id="<?= $content['post_id']; ?>" data-type="post">
              <i class="bi bi-trash size-21 red"></i>
            </span>
            <a class="font-normal black size-24 mt0 mb0" href="<?= getUrlByName('post', ['id' => $content['post_id'], 'slug' => $content['post_slug']]); ?>">
              <?= $content['post_title']; ?>
            </a>
          <?php } else { ?>
            <span id="fav-comm" class="add-favorite right  ml15 size-14" data-front="personal" data-id="<?= $content['answer_id']; ?>" data-type="answer">
              <i class="bi bi-trash size-21 red"></i>
            </span>
            <a class="title" href="<?= getUrlByName('post', ['id' => $content['post']['post_id'], 'slug' => $content['post']['post_slug']]); ?>#answer_<?= $content['answer_id']; ?>">
              <?= $content['post']['post_title']; ?>
            </a>
          <?php } ?>
        </h3>
        <?php if (!empty($content['favorite_type']) == 2) {
          echo $content['answer_content'];
        } ?>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'there are no favorites']); ?>
  <?php } ?>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('info-favorite')]); ?>