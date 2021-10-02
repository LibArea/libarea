<div class="bg-white br-rd-5 border-box-1 sticky p15">
  <div class="menu-info">
    <a title="<?= lang('profile'); ?>" class="mb5 size-15 block gray" href="<?= getUrlByName('user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-person middle"></i>
      <span class="middle"><?= lang('profile'); ?></span>
    </a>
    <a title="<?= lang('Posts'); ?>" class="mb5 size-15 block gray<?php if ($sheet == 'user-post') { ?> red<?php } ?>" href="<?= getUrlByName('posts.user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-journal-text middle"></i>
      <span class="middle"><?= lang('posts'); ?></span>
    </a>
    <a title="<?= lang('answers-n'); ?>" class="mb5 size-15 block gray<?php if ($sheet == 'user-answers') { ?> red<?php } ?>" href="<?= getUrlByName('answers.user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-chat-left-text middle"></i>
      <span class="middle"><?= lang('answers-n'); ?></span>
    </a>
    <a title="<?= lang('comments-n'); ?>" class="mb5 size-15 block gray<?php if ($sheet == 'user-comments') { ?> red<?php } ?>" href="<?= getUrlByName('comments.user', ['login' => Request::get('login')]); ?>">
      <i class="bi bi-chat-dots middle"></i>
      <span class="middle"><?= lang('comments-n'); ?></span>
    </a>
  </div>
</div>