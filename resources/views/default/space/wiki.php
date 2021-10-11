<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <?= breadcrumb('/', lang('home'), '/s/' . $data['space']['space_slug'], $data['space']['space_name'], lang('Wiki')); ?>

  <?php if ($data['space']['space_wiki']) { ?>
    <div class="bg-white br-rd-5 border-box-1 p15">
      <h1><?= lang('Wiki'); ?> <span class="lowercase"><?= lang('page'); ?></span></h1>
      <?= $data['space']['space_wiki']; ?>
    </div>
  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
  <?php } ?>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('under development')]); ?>