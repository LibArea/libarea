<div class="bg-white br-rd5 border-box-1 sticky p15">
  <div class="menu-info">
    <a title="<?= Translate::get('profile'); ?>" class="mb5 size-15 block gray" href="<?= getUrlByName('user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-person middle"></i>
      <span class="middle"><?= Translate::get('profile'); ?></span>
    </a>
    <a title="<?= Translate::get('Posts'); ?>" class="mb5 size-15 block gray<?php if ($sheet == 'user-post') { ?> red<?php } ?>" href="<?= getUrlByName('posts.user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-journal-text middle"></i>
      <span class="middle"><?= Translate::get('posts'); ?></span>
    </a>
    <a title="<?= Translate::get('answers'); ?>" class="mb5 size-15 block gray<?php if ($sheet == 'user-answers') { ?> red<?php } ?>" href="<?= getUrlByName('answers.user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-chat-left-text middle"></i>
      <span class="middle"><?= Translate::get('answers'); ?></span>
    </a>
    <a title="<?= Translate::get('comments'); ?>" class="mb5 size-15 block gray<?php if ($sheet == 'user-comments') { ?> red<?php } ?>" href="<?= getUrlByName('comments.user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-chat-dots middle"></i>
      <span class="middle"><?= Translate::get('comments'); ?></span>
    </a>
  </div>
</div>