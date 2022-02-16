<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]);
$domain = $data['domain'];
$breadcrumb = (new Breadcrumbs('<span>/</span>'))->base('/', Translate::get('home'));
$breadcrumb->addCrumb('site.edit', 'red');
$form = new Forms();
$form->add_input(Translate::get('title'), ['value' => $domain['item_title_url'], 'min' => 14, 'max' => 250, 'required' => true], 'title');
$form->add_input('URL', ['value' => $domain['item_url'],'required' => true], 'url');
$form->add_input(Translate::get('status'), ['value' => $domain['item_status_url'], 'required' => true], 'status');
$form->add_input(Translate::get('description'), ['value' => $domain['item_content_url'], 'min' => 14, 'max' => 550,  'type' => 'textarea', 'required' => true], 'content');
?>
<main class="col-span-12 mb-col-12">
  <div class="box max-w780">

    <?= $breadcrumb->render('bread_crumbs'); ?>

    <fieldset class="gray-400">
      <?= $domain['item_id']; ?>. <?= $domain['item_url_domain']; ?>
      <?= website_img($domain['item_url_domain'], 'favicon', $domain['item_url_domain'], ' ml10'); ?>
      <span class="add-favicon text-sm" data-id="<?= $domain['item_id']; ?>">+ favicon</span>
    </fieldset>

    <form action="<?= getUrlByName('web.edit.pr'); ?>" method="post">
      <?= csrf_field() ?>

      <?= Tpl::insert('/_block/form/select/select', [
        'data'          => $data,
        'action'        => 'edit',
        'type'          => 'category',
        'title'         => Translate::get('topics'),
        'required'      => false,
        'maximum'       => 3,
        'help'          => Translate::get('necessarily'),
        'red'           => 'red'
      ]); ?>

      <?= $form->build_form(); ?>

      <?= Tpl::insert('/_block/form/radio', [
        'data' => [
          [
            'title'   => Translate::get('posted') . '?',
            'name'    => 'item_published',
            'checked' => $domain['item_published'],
            'help'    => Translate::get('posted-help'),
          ],
        ]
      ]); ?>

      <h3 class="mb5"><?= Translate::get('soft'); ?></h3>
      <?= Tpl::insert('/_block/form/radio', [
        'data' => [
          [
            'title'   => Translate::get('there.program'),
            'name'    => 'item_is_soft',
            'checked' => $domain['item_is_soft'],
          ],
          [
            'title'   => Translate::get('hosted.github'),
            'name'    => 'item_is_github',
            'checked' => $domain['item_is_github'],
          ],
        ]
      ]); ?>

      <?= Tpl::insert('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('url.address.github'),
            'type'  => 'text',
            'name'  => 'item_github_url',
            'value' => $domain['item_github_url'],
          ],
          [
            'title' => Translate::get('title'),
            'type'  => 'text',
            'name'  => 'item_title_soft',
            'value' => $domain['item_title_soft'],
          ],
        ]
      ]); ?>

      <?php Tpl::insert('/_block/editor/textarea', [
        'title'     => Translate::get('description'),
        'type'      => 'text',
        'name'      => 'item_content_soft',
        'content'   => $domain['item_content_soft'],
        'min'       => 24,
        'max'       => 1500,
        'help'      => '24 - 1500 ' . Translate::get('characters')
      ]); ?>

      <?= Tpl::insert('/_block/form/select/related-posts', [
        'data'      => $data,
        'action'    => 'edit',
        'type'      => 'post',
        'title'     => Translate::get('related posts'),
        'help'      => Translate::get('necessarily'),
      ]); ?>

      <input type="hidden" name="item_id" value="<?= $domain['item_id']; ?>">

      <?= $form->sumbit(Translate::get('edit')); ?>
    </form>
  </div>
</main>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>