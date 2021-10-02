<nav class="justify-between mt0 ml0 pl0 t-81 sticky size-15">
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('admin'); ?>" href="<?= getUrlByName('admin'); ?>">
    <i class="bi bi-shield-exclamation middle mr5<?= $sheet == 'admin' ? ' blue' : ''; ?>  size-18"></i>
    <span class="<?= $sheet == 'admin' ? 'blue' : ''; ?>"><?= lang('admin'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('users'); ?>" href="<?= getUrlByName('admin.users'); ?>">
    <i class="bi bi-people middle mr5<?= $sheet == 'users' ? ' blue' : ''; ?>  size-18"></i>
    <span class="<?= $sheet == 'users' ? 'blue' : ''; ?>"><?= lang('users'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" class="block mb5" title="<?= lang('reports'); ?>" href="<?= getUrlByName('admin.reports'); ?>">
    <i class="bi bi-flag middle mr5<?= $sheet == 'reports' ? ' blue' : ''; ?> size-18"></i>
    <span class="<?= $sheet == 'reports' ? 'blue' : ''; ?>"><?= lang('reports'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('audits'); ?>" href="<?= getUrlByName('admin.audits'); ?>">
    <i class="bi bi-exclamation-diamond middle mr5<?= $sheet == 'approved' ? ' blue' : ''; ?><?= $sheet == 'audits' ? ' blue' : ''; ?>"></i>
    <span class="<?= $sheet == 'approved' ? ' blue' : ''; ?><?= $sheet == 'audits' ? 'blue' : ''; ?>"><?= lang('audits'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('spaces'); ?>" href="/admin/spaces">
    <i class="bi bi-command middle mr5<?= $sheet == 'spaces' ? ' blue' : ''; ?>"></i>
    <span class="<?= $sheet == 'spaces' ? 'blue' : ''; ?>"><?= lang('spaces'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('topics'); ?>" href="<?= getUrlByName('admin.topics'); ?>">
    <i class="bi bi-columns-gap middle mr5<?= $sheet == 'topics' ? ' blue' : ''; ?>"></i>
    <span class="<?= $sheet == 'topics' ? 'blue' : ''; ?>"><?= lang('topics'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('invites'); ?>" href="<?= getUrlByName('admin.invitations'); ?>">
    <i class="bi bi-person-plus middle mr5<?= $sheet == 'invitations' ? ' blue' : ''; ?>"></i>
    <span class="<?= $sheet == 'invitations' ? 'blue' : ''; ?>"><?= lang('invites'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('posts'); ?>" href="<?= getUrlByName('admin.posts'); ?>">
    <i class="bi bi-journal-text middle mr5<?= $sheet == 'posts-ban' ? ' blue' : ''; ?><?= $sheet == 'posts' ? ' blue' : ' gray-light-2'; ?> size-18"></i>
    <span class="<?= $sheet == 'posts-ban' ? ' blue' : ''; ?><?= $sheet == 'posts' ? 'blue' : ''; ?>"><?= lang('posts'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('comments-n'); ?>" href="<?= getUrlByName('admin.comments'); ?>">
    <i class="bi bi-chat-dots middle mr5<?= $sheet == 'comments-n' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'comments-n' ? 'blue' : ''; ?>"><?= lang('comments-n'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('answers-n'); ?>" href="<?= getUrlByName('admin.answers'); ?>">
    <i class="bi bi-chat-left-text middle mr5<?= $sheet == 'answers-n' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'answers-n' ? 'blue' : ''; ?>"><?= lang('answers-n'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('badges'); ?>" href="<?= getUrlByName('admin.badges'); ?>">
    <i class="bi bi-award middle mr5<?= $sheet == 'badges' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'badges' ? 'blue' : ''; ?>"><?= lang('badges'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('domains'); ?>" href="<?= getUrlByName('admin.webs'); ?>">
    <i class="bi bi-link-45deg middle mr5<?= $sheet == 'domains' ? ' blue' : ''; ?>"></i>
    <span class="middle size-14<?= $sheet == 'domains' ? ' blue' : ''; ?>"><?= lang('domains'); ?></span>
  </a>
  <a class="pt5 pr10 pb5 pl10 block gray bg-hover-100" title="<?= lang('stop words'); ?>" href="<?= getUrlByName('admin.words'); ?>">
    <i class="bi bi-badge-ad middle mr5<?= $sheet == 'words' ? ' blue' : ''; ?>  size-18"></i>
    <span class="middle size-14<?= $sheet == 'words' ? ' blue' : ''; ?>"><?= lang('stop words'); ?></span>
  </a>
  <hr>
  Agouti &copy; <?= date('Y'); ?>
</nav>