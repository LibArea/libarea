<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]);
$domain = $data['domain'];
$form = new Forms();

$form->adding(['name' => 'title', 'type' => 'value', 'var' => $domain['item_title']]);
$form->adding(['name' => 'url', 'type' => 'value', 'var' => $domain['item_url']]);
$form->adding(['name' => 'status', 'type' => 'value', 'var' => $domain['item_status_url']]);
$form->adding(['name' => 'content', 'type' => 'value', 'var' => $domain['item_content']]);
$form->adding(['name' => 'published', 'type' => 'selected', 'var' => $domain['item_published']]);
$form->adding(['name' => 'soft', 'type' => 'selected', 'var' => $domain['item_is_soft']]);
$form->adding(['name' => 'github', 'type' => 'selected', 'var' => $domain['item_is_github']]);
$form->adding(['name' => 'github_url', 'type' => 'value', 'var' => $domain['item_github_url']]);
$form->adding(['name' => 'title_soft', 'type' => 'value', 'var' => $domain['item_title_soft']]);
$form->adding(['name' => 'content_soft', 'type' => 'value', 'var' => $domain['item_content_soft']]);
$form->adding(['name' => 'close_replies', 'type' => 'selected', 'var' => $domain['item_close_replies']]);

$form->html_form($user['trust_level'], Config::get('form/catalog.site'));
?>

<div id="contentWrapper">
  <main>
    <div class="max-w640">

    <?= Tpl::insert('/_block/navigation/breadcrumbs', [
        'list' => [
          [
            'name' => __('home'),
            'link' => getUrlByName('web')
          ], [
            'name' => __('site.edit'),
            'link' => 'red'
          ],
        ]
      ]); ?>

      <fieldset class="gray-600">
        <?= $domain['item_id']; ?>. <?= $domain['item_domain']; ?>
        <?= Html::websiteImage($domain['item_domain'], 'favicon', $domain['item_domain'], ' ml10'); ?>
        <span class="add-favicon text-sm" data-id="<?= $domain['item_id']; ?>">+ favicon</span>
      </fieldset>

      <form action="<?= getUrlByName('web.change'); ?>" method="post">
        <?= csrf_field() ?>

        <?= includeTemplate('/view/default/_block/category', ['data' => $data, 'action' => 'edit']); ?>

        <?= $form->build_form(); ?>

        <?= Tpl::insert('/_block/form/select/related-posts', [
          'data'      => $data,
          'action'    => 'edit',
          'type'      => 'post',
          'title'     => __('related posts'),
          'help'      => __('necessarily'),
        ]); ?>
        
         <?php if (UserData::checkAdmin()) { ?>
            <?= Tpl::insert('/_block/form/select/user', [
              'uid'           => $user,
              'user'          => $data['user'],
              'action'        => 'user',
              'type'          => 'user',
              'title'         => __('author'),
              'help'          => __('necessarily'),
            ]); ?>
        <?php } ?>

        <input type="hidden" name="item_id" value="<?= $domain['item_id']; ?>">

        <?= $form->sumbit(__('edit')); ?>
      </form>
    </div>
  </main>
  <aside>
    <div class="box-white box-shadow-all text-sm">
      <h3 class="uppercase-box"><?= __('help'); ?></h3>
      <?= __('add.site.help'); ?>
      <div>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>