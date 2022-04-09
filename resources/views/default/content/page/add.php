<main class="col-two">
  <div class="bg-white items-center justify-between br-box-gray br-rd5 p15 mb15">

    <a href="/"><?= __('home'); ?></a> /
    <span class="red"><?= sprintf(__('add.option'), __('pages')); ?></span>

    <form action="<?= getUrlByName('content.create', ['type' => 'page']); ?>" method="post">
      <?= csrf_field() ?>

      <fieldset>
        <label for="post_title"><?= __('heading'); ?></label>
        <input minlength="6" maxlength="250" id="title" type="text" required="" name="post_title">
        <div class="help">6 - 250 <?= __('characters'); ?></div>
      </fieldset>

      <?php if (!empty($data['blog'])) { ?>
        <?= Tpl::insert('/_block/form/select/blog', [
          'data'        => $data,
          'action'      => 'add',
          'type'        => 'blog',
          'title'       => __('blogs'),
        ]); ?>
      <?php } ?>

      <?php if (UserData::checkAdmin()) { ?>
        <?= Tpl::insert('/_block/form/select/section', [
          'data'          => $data['facets'],
          'type'          => 'section',
          'action'        => 'add',
          'title'         => __('section'),
          'help'          => __('necessarily'),
          'red'           => 'red'
        ]); ?>
      <?php } ?>

      <?= Tpl::insert('/_block/editor/editor', ['height'  => '250px', 'type' => 'page', 'id' => 0]); ?>

      <?= Html::sumbit(__('create')); ?>
    </form>
  </div>
</main>