<main class="col-two">
  <div class="box">

    <a href="/"><?= __('home'); ?></a> /
    <span class="red"><?= __('add.option', ['name' => __('pages')]); ?></span>

    <form action="<?= getUrlByName('content.create', ['type' => 'page']); ?>" method="post">
      <?= csrf_field() ?>

      <fieldset>
        <label for="post_title"><?= __('heading'); ?></label>
        <input minlength="6" maxlength="250" id="title" type="text" required="" name="post_title">
        <div class="help">6 - 250 <?= __('characters'); ?></div>
      </fieldset>

      <?php if (!empty($data['blog'])) : ?>
        <?= Tpl::insert('/_block/form/select/blog', [
          'data'        => $data,
          'action'      => 'add',
          'type'        => 'blog',
          'title'       => __('blogs'),
        ]); ?>
      <?php endif; ?>

      <?php if (UserData::checkAdmin()) : ?>
        <?= Tpl::insert('/_block/form/select/section', [
          'data'          => $data['facets'],
          'type'          => 'section',
          'action'        => 'add',
          'title'         => __('section'),
          'help'          => __('necessarily'),
          'red'           => 'red'
        ]); ?>
      <?php endif; ?>

      <?= Tpl::insert('/_block/editor/editor', ['height'  => '250px', 'type' => 'page', 'id' => 0]); ?>

      <?= Html::sumbit(__('create')); ?>
    </form>
  </div>
</main>