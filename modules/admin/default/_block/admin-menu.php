<aside class="left p0 bg-gray-800 fixed">
  <div class="logo size-15 mt15 ml15 flex">
    <a title="<?= lang('Admin'); ?>" class="light-gray" href="<?= getUrlByName('admin'); ?>">
      <?= lang('Admin'); ?>
    </a>
    <span class="gray ml15 mr15">|</span>
    <a title="<?= lang('in-the-profile'); ?> <?= $uid['user_login']; ?>" class="light-gray size-15" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>">
      <?= user_avatar_img($uid['user_avatar'], 'max', $uid['user_id'], 'ava-24 mr5'); ?>
      <?= $uid['user_login']; ?>
    </a>
  </div>
  <div class="ml15 gray size-13">--------------------------------------------</div>
  <ul class="p0 mt5 mb5">
    <li class="nav<?php if ($meta['sheet'] == 'admin') { ?> active<?php } ?>">
      <a title="<?= lang('Aadmin'); ?>" href="<?= getUrlByName('admin'); ?>">
        <i class="icon-tools middle mr10"></i>
        <span class="size-15 middle"><?= lang('Admin'); ?></span>
      </a>
    <li>
    <li class="nav<?php if ($meta['sheet'] == 'users') { ?> active<?php } ?>">
      <a title="<?= lang('Users'); ?>" href="<?= getUrlByName('admin.users'); ?>">
        <i class="icon-user-o middle mr10"></i>
        <span class="size-15 middle"><?= lang('Users'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'reports') { ?> active<?php } ?>">
      <a title="<?= lang('Reports'); ?>" href="<?= getUrlByName('admin.reports'); ?>">
        <i class="icon-warning-empty middle mr10"></i>
        <span class="size-15 middle"><?= lang('Reports'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'audits') { ?> active<?php } ?>">
      <a title="<?= lang('Audit'); ?>" href="<?= getUrlByName('admin.audits'); ?>">
        <i class="icon-lightbulb middle mr10"></i>
        <span class="size-15 middle"><?= lang('Audit'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'spaces') { ?> active<?php } ?>">
      <a title="<?= lang('Spaces'); ?>" href="/admin/spaces">
        <i class="icon-infinity middle mr10"></i>
        <span class="size-15 middle"><?= lang('Spaces'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'topics') { ?> active<?php } ?>">
      <a title="<?= lang('Topics'); ?>" href="<?= getUrlByName('admin.topics'); ?>">
        <i class="icon-clone middle mr10"></i>
        <span class="size-15 middle"><?= lang('Topics'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'invitations') { ?> active<?php } ?>">
      <a title="<?= lang('Invites'); ?>" href="<?= getUrlByName('admin.invitations'); ?>">
        <i class="icon-user-add-outline middle mr10"></i>
        <span class="size-15 middle"><?= lang('Invites'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'posts') { ?> active<?php } ?>">
      <a title="<?= lang('Invites'); ?>" href="<?= getUrlByName('admin.posts'); ?>">
        <i class="icon-book-open middle mr10"></i>
        <span class="size-15 middle"><?= lang('Posts'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'comments') { ?> active<?php } ?>">
      <a title="<?= lang('Comments-n'); ?>" href="<?= getUrlByName('admin.comments'); ?>">
        <i class="icon-commenting-o middle mr10"></i>
        <span class="size-15 middle"><?= lang('Comments-n'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'answers') { ?> active<?php } ?>">
      <a title="<?= lang('Answers-n'); ?>" href="<?= getUrlByName('admin.answers'); ?>">
        <i class="icon-comment-empty middle mr10"></i>
        <span class="size-15 middle"><?= lang('Answers-n'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'badges') { ?> active<?php } ?>">
      <a title="<?= lang('Badges'); ?>" href="<?= getUrlByName('admin.badges'); ?>">
        <i class="icon-award middle mr10"></i>
        <span class="size-15 middle"><?= lang('Badges'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'domains') { ?> active<?php } ?>">
      <a title="<?= lang('Domains'); ?>" href="<?= getUrlByName('admin.webs'); ?>">
        <i class="icon-link middle mr10"></i>
        <span class="size-15 middle"><?= lang('Domains'); ?></span>
      </a>
    </li>
    <li class="nav<?php if ($meta['sheet'] == 'words') { ?> active<?php } ?>">
      <a title="<?= lang('Stop words'); ?>" href="<?= getUrlByName('admin.words'); ?>">
        <i class="icon-info middle mr10"></i>
        <span class="size-15 middle"><?= lang('Stop words'); ?></span>
      </a>
    </li>
  </ul>

  <div class="center gray size-13 mb15">
    ------------------------
    <br>
    Agouti &copy; <?= date('Y'); ?>
  </div>
</aside>