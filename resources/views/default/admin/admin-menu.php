<nav class="justify-between mt0 ml0 pl0 t-81 sticky size-15">
  <a class="block mb5" title="<?= lang('admin'); ?>" href="<?= getUrlByName('admin'); ?>">
    <i class="icon-tools<?= $sheet == 'admin' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'admin' ? 'blue' : 'black'; ?>"><?= lang('admin'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('users'); ?>" href="<?= getUrlByName('admin.users'); ?>">
    <i class="icon-user-o<?= $sheet == 'users' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'users' ? 'blue' : 'black'; ?>"><?= lang('users'); ?></span>
  </a>
  <a class="block mb5" class="block mb5" title="<?= lang('reports'); ?>" href="<?= getUrlByName('admin.reports'); ?>">
    <i class="icon-warning-empty<?= $sheet == 'reports' ? ' blue' : ' gray-light-2'; ?> size-18"></i>
    <span class="<?= $sheet == 'reports' ? 'blue' : 'black'; ?>"><?= lang('reports'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('audits'); ?>" href="<?= getUrlByName('admin.audits'); ?>">
    <i class="icon-lightbulb<?= $sheet == 'approved' ? ' blue' : ''; ?><?= $sheet == 'audits' ? ' blue' : ' gray-light-2'; ?> size-18"></i>
    <span class="<?= $sheet == 'approved' ? ' blue' : ''; ?><?= $sheet == 'audits' ? 'blue' : 'black'; ?>"><?= lang('audits'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('spaces'); ?>" href="/admin/spaces">
    <i class="icon-infinity<?= $sheet == 'spaces' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'spaces' ? 'blue' : 'black'; ?>"><?= lang('spaces'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('topics'); ?>" href="<?= getUrlByName('admin.topics'); ?>">
    <i class="icon-clone<?= $sheet == 'topics' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'topics' ? 'blue' : 'black'; ?>"><?= lang('topics'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('invites'); ?>" href="<?= getUrlByName('admin.invitations'); ?>">
    <i class="icon-user-add-outline<?= $sheet == 'invitations' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'invitations' ? 'blue' : 'black'; ?>"><?= lang('invites'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('posts'); ?>" href="<?= getUrlByName('admin.posts'); ?>">
    <i class="icon-book-open<?= $sheet == 'posts-ban' ? ' blue' : ''; ?><?= $sheet == 'posts' ? ' blue' : ' gray-light-2'; ?> size-18"></i>
    <span class="<?= $sheet == 'posts-ban' ? ' blue' : ''; ?><?= $sheet == 'posts' ? 'blue' : 'black'; ?>"><?= lang('posts'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('comments-n'); ?>" href="<?= getUrlByName('admin.comments'); ?>">
    <i class="icon-commenting-o<?= $sheet == 'comments-n' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'comments-n' ? 'blue' : 'black'; ?>"><?= lang('comments-n'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('answers-n'); ?>" href="<?= getUrlByName('admin.answers'); ?>">
    <i class="icon-comment-empty<?= $sheet == 'answers-n' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'answers-n' ? 'blue' : 'black'; ?>"><?= lang('answers-n'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('badges'); ?>" href="<?= getUrlByName('admin.badges'); ?>">
    <i class="icon-award<?= $sheet == 'badges' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'badges' ? 'blue' : 'black'; ?>"><?= lang('badges'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('domains'); ?>" href="<?= getUrlByName('admin.webs'); ?>">
    <i class="icon-link<?= $sheet == 'domains' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'domains' ? 'blue' : 'black'; ?>"><?= lang('domains'); ?></span>
  </a>
  <a class="block mb5" title="<?= lang('stop words'); ?>" href="<?= getUrlByName('admin.words'); ?>">
    <i class="icon-info<?= $sheet == 'words' ? ' blue' : ' gray-light-2'; ?>  size-18"></i>
    <span class="<?= $sheet == 'words' ? 'blue' : 'black'; ?>"><?= lang('stop words'); ?></span>
  </a>
  <hr>
  Agouti &copy; <?= date('Y'); ?>
</nav>