<main class="col-two">
  <div class="bg-white items-center justify-between br-box-gray br-rd5 p15 mb15">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500"><?= sprintf(Translate::get('add.option'), Translate::get('pages')); ?></span>
    
    <form action="<?= getUrlByName('content.create', ['type' => 'page']); ?>" method="post">
      <?= csrf_field() ?>

    <fieldset>
      <label for="post_title"><?= Translate::get('heading'); ?></label>
      <input minlength="6" maxlength="250" id="title" type="text" required="" name="post_title">
      <div class="help">6 - 250 <?= Translate::get('characters'); ?></div>  
    </fieldset>

      <?php if (!empty($data['blog'])) { ?>
        <?= Tpl::import('/_block/form/select/blog', [
          'data'        => $data,
          'action'      => 'add',
          'type'        => 'blog',
          'title'       => Translate::get('blogs'),
        ]); ?>
      <?php } ?>

      <?php if (UserData::checkAdmin()) { ?>
        <?= Tpl::import('/_block/form/select/section', [
          'data'          => $data['facets'],
          'type'          => 'section',
          'action'        => 'add',
          'title'         => Translate::get('section'),
          'help'          => Translate::get('necessarily'),
          'red'           => 'red'
        ]); ?>
      <?php } ?>

      <?= Tpl::import('/_block/editor/editor', ['height'  => '250px', 'type' => 'page', 'id' => 0]); ?>

      <?= sumbit(Translate::get('create')); ?>
    </form>
  </div>
</main>