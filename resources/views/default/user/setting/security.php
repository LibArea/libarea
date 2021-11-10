<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">

  <div class="bg-white flex flex-row items-center justify-between br-box-grey br-rd5 p15 mb15">
    <p class="m0 no-mob"><?= Translate::get($data['sheet']); ?></p>
    <?= includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <div class=" bg-white br-box-grey pt15 pr15 pb5 pl15 box setting avatar">
    <form action="/users/setting/security/edit" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>

      <?= includeTemplate('/_block/form/field-input', ['data' => [
        [
          'title' => Translate::get('old'),
          'type' => 'text',
          'name' => 'password',
          'value' => ''
        ],
        [
          'title' => Translate::get('new'),
          'type' => 'password',
          'name' => 'password2',
          'value' => '',
          'min' => 6,
          'max' => 32,
          'help' => '6 - 32 ' . Translate::get('characters')
        ],
        [
          'title' => Translate::get('repeat'),
          'type' => 'password',
          'name' => 'password3',
          'value' => ''
        ],
      ]]); ?>

      <div class="mb20">
        <input type="hidden" name="nickname" id="nickname" value="">
        <?= sumbit(Translate::get('edit')); ?>
      </div>
    </form>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => Translate::get('info-security')]); ?>