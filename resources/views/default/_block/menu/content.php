<div class="sticky top60">
  <div class="bg-white br-rd5 br-box-gray p15 size-15">
    <a title="<?= Translate::get('profile'); ?>" class="mb10 block gray" href="<?= getUrlByName('user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-person middle mr5 size-18"></i>
      <span class="middle"><?= Translate::get('profile'); ?></span>
    </a>
    <a title="<?= Translate::get('Posts'); ?>" class="mb10 block gray<?php if ($sheet == 'user-post') { ?> red<?php } ?>" href="<?= getUrlByName('posts.user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-journal-text middle mr5 size-18"></i>
      <span class="middle"><?= Translate::get('posts'); ?></span>
    </a>
    <a title="<?= Translate::get('answers'); ?>" class="mb10 block gray<?php if ($sheet == 'user-answers') { ?> red<?php } ?>" href="<?= getUrlByName('answers.user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-chat-dots middle mr5 size-18"></i>
      <span class="middle"><?= Translate::get('answers'); ?></span>
    </a>
    <a title="<?= Translate::get('comments'); ?>" class="mb10 block gray<?php if ($sheet == 'user-comments') { ?> red<?php } ?>" href="<?= getUrlByName('comments.user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-chat-quote middle mr5 size-18"></i>
      <span class="middle"><?= Translate::get('comments'); ?></span>
    </a>
  </div>
<?= includeTemplate('/_block/sidebar/footer'); ?>
</div>
 