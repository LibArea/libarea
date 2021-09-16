<div class="white-box sticky p15">
  <div class="menu-info">
    <a title="<?= lang('Profile'); ?>" class="mb5 size-15 block gray" href="<?= getUrlByName('user', ['login' => Request::get('login')]); ?>">
      <i class="icon-user-o middle"></i>
      <span class="middle"><?= lang('Profile'); ?></span>
    </a>
    <a title="<?= lang('Posts'); ?>" class="mb5 size-15 block gray<?php if ($sheet == 'user-post') { ?> red<?php } ?>" href="<?= getUrlByName('posts.user', ['login' => Request::get('login')]); ?>">
      <i class="icon-book-open middle"></i>
      <span class="middle"><?= lang('Posts'); ?></span>
    </a>
    <a title="<?= lang('Answers-n'); ?>" class="mb5 size-15 block gray<?php if ($sheet == 'user-answers') { ?> red<?php } ?>" href="<?= getUrlByName('answers.user', ['login' => Request::get('login')]); ?>">
      <i class="icon-comment-empty middle"></i>
      <span class="middle"><?= lang('Answers-n'); ?></span>
    </a>
    <a title="<?= lang('Comments-n'); ?>" class="mb5 size-15 block gray<?php if ($sheet == 'user-comments') { ?> red<?php } ?>" href="<?= getUrlByName('comments.user', ['login' => Request::get('login')]); ?>">
      <i class="icon-commenting-o middle"></i>
      <span class="middle"><?= lang('Comments-n'); ?></span>
    </a>
  </div>
</div>