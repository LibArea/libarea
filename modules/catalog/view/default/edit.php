<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]);
$domain = $data['domain'];
$breadcrumb = (new Breadcrumbs())->base(getUrlByName('web'), Translate::get('home'));
$breadcrumb->addCrumb(Translate::get('site.edit'), 'red');
$form = new Forms();

$form->adding(['name' => 'title', 'type' => 'value', 'var' => $domain['item_title_url']]);
$form->adding(['name' => 'url', 'type' => 'value', 'var' => $domain['item_url']]);
$form->adding(['name' => 'status', 'type' => 'value', 'var' => $domain['item_status_url']]);
$form->adding(['name' => 'content', 'type' => 'value', 'var' => $domain['item_content_url']]);
$form->adding(['name' => 'published', 'type' => 'selected', 'var' => $domain['item_published']]);
$form->adding(['name' => 'soft', 'type' => 'selected', 'var' => $domain['item_is_soft']]);
$form->adding(['name' => 'github', 'type' => 'selected', 'var' => $domain['item_is_github']]);
$form->adding(['name' => 'github_url', 'type' => 'value', 'var' => $domain['item_github_url']]);
$form->adding(['name' => 'title_soft', 'type' => 'value', 'var' => $domain['item_title_soft']]);
$form->adding(['name' => 'content_soft', 'type' => 'value', 'var' => $domain['item_content_soft']]);

$form->html_form($user['trust_level'], Config::get('form/catalog.site'));
?>

<div class="grid grid-cols-12 gap-4">
<main>
  <div class="max-w640">

    <?= $breadcrumb->render('breadcrumbs'); ?>

    <fieldset class="gray-400">
      <?= $domain['item_id']; ?>. <?= $domain['item_url_domain']; ?>
      <?= website_img($domain['item_url_domain'], 'favicon', $domain['item_url_domain'], ' ml10'); ?>
      <span class="add-favicon text-sm" data-id="<?= $domain['item_id']; ?>">+ favicon</span>
    </fieldset>

    <form action="<?= getUrlByName('web.edit.pr'); ?>" method="post">
      <?= csrf_field() ?>

      <?= includeTemplate('/view/default/_block/category', ['data' => $data, 'action' => 'edit']); ?>

      <?= $form->build_form(); ?>

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
<aside>
  <div class="box-white box-shadow-all text-sm">
    <h3 class="uppercase-box"><?= Translate::get('help'); ?></h3>
    <?= Translate::get('add.site.help'); ?>
  <div>
</aside>
</div> 
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>