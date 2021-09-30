<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('home'), getUrlByName('user', ['login' => Request::get('login')]), lang('profile'), $data['h1']); ?>
  </div>
  <?php if (!empty($data['comments'])) { ?>
    <?php foreach ($data['comments'] as $comm) { ?>
      <div class="bg-white br-rd-5 mt15 border-box-1 pt15 pr15 pb0 pl15">
        <div class="size-14 gray">
          <a class="gray" href="<?= getUrlByName('user', ['login' => $comm['user_login']]); ?>">
            <?= user_avatar_img($comm['user_avatar'], 'max', $comm['user_login'], 'w18 mr5'); ?>
            <?= $comm['user_login']; ?>
          </a>
          <span class="mr5 ml5 gray lowercase">
            <?= $comm['date']; ?>
          </span>
        </div>
        <a class="mr5 mb5 block" href="<?= getUrlByName('post', ['id' => $comm['post_id'], 'slug' => $comm['post_slug']]); ?>">
          <?= $comm['post_title']; ?>
        </a>
        <p><?= $comm['comment_content']; ?></p>
        <div class="pr15 pb5 hidden gray">
          + <?= $comm['comment_votes']; ?>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'there are no comments']); ?>
  <?php } ?>
</main>
<aside class="col-span-3 no-mob">
  <?= includeTemplate('/_block/user-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
</aside>