<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => 'tools', 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), null, null, lang('tools')); ?>
  <div class="boxline">
    <label class="required block pb5"><?= lang('topics'); ?> / <?= lang('posts'); ?></label>
    <a class="size-14 mt5 button white" href="<?= getUrlByName('admin.count.topic');?>">
      <?= lang('update the data'); ?>
    </a>
  </div>
  <div class="boxline">
    <label class="required block pt15 pb5"><?= lang('like'); ?></label>
    <a class="size-14 mt5 button white" href="<?= getUrlByName('admin.count.up');?>">
      <?= lang('update the data'); ?>
    </a>
  </div>
  <div class="boxline">
    <label class="required block pt15 pb5"><?= lang('Email'); ?></label>
    <form action="<?= getUrlByName('admin.test.mail');?>" method="post">
        <input class="form-input" type="mail" name="mail" value="">
        <div class="size-14 gray-light-2"><?= lang('test-email'); ?>...</div>
        <input type="submit" class="button block mt5 br-rd-5 white" name="submit" value="<?= lang('send'); ?>" />
    </form>
  </div>
</main>