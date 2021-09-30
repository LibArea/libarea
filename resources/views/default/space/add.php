<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('home'), '/spaces', lang('spaces'), lang('add Space')); ?>

    <form action="/space/create" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= includeTemplate('/_block/form/field-input', ['data' => [
        ['title' => 'URL (slug)', 'type' => 'text', 'name' => 'space_slug', 'value' => '', 'min' => 3, 'max' => 12, 'help' => '3 - 12 ' . lang('characters') . ' (' . lang('english') . ')'],
        ['title' => lang('title'), 'type' => 'text', 'name' => 'space_name', 'value' => '', 'min' => 4, 'max' => 18, 'help' => '4 - 18 ' . lang('characters')],
      ]]); ?>

      <?= includeTemplate('/_block/form/field-radio', ['data' => [
        ['title' => lang('publications'), 'name' => 'permit', 'checked' => 0, 'help' => lang('who will be able to post')],
        ['title' => lang('to close?'), 'name' => 'feed', 'checked' => 0, 'help' => lang('posts will not be visible in the feed')],
      ]]); ?>

      <div class="boxline">
        <input type="submit" class="button block br-rd-5 white" name="submit" value="<?= lang('add'); ?>" />
      </div>
    </form>
    <div class="boxline">
      <?= lang('you can add spaces'); ?>: <b><?= $data['num_add_space']; ?></b>
    </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('under development')]); ?>