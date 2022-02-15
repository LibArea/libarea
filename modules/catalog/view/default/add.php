<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]);
$form = new Forms();
$form->add_input(Translate::get('title'), ['min' => 14, 'max' => 250, 'required' => true], 'title');
$form->add_input('URL', ['required' => true], 'url');
$form->add_input(Translate::get('description'), ['type' => 'textarea', 'required' => true], 'content');
?>

<main class="col-span-12 mb-col-12">
  <div class="box max-w780">
    <?= $data['breadcrumb']; ?>

    <form id="addUrl" class="max-w780">
      <?= csrf_field() ?>
      <?= $form->build_form(); ?>

      <?= Tpl::insert('/_block/form/select/select', [
        'data'      => ['topic' => false],
        'type'      => 'category',
        'action'    => 'add',
        'title'     => Translate::get('facets'),
        'help'      => Translate::get('necessarily'),
        'red'       => 'red'
      ]); ?>

      <?= $form->sumbit(Translate::get('add')); ?>
    </form>
  </div>
</main>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    const ajaxSend = async (formData) => {
      const fetchResp = await fetch('<?= getUrlByName('web.create'); ?>', {
        method: 'POST',
        body: formData
      });
      if (!fetchResp.ok) {
        throw new Error(`error url ${url}, status: ${fetchResp.status}`);
      }
      return await fetchResp.text();
    };

    const forms = document.querySelectorAll('form#addUrl');
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        ajaxSend(formData)
          .then((response) => {
            let is_valid = JSON.parse(response);
            if (is_valid.error == 'error') {
              Notiflix.Notify.failure(is_valid.text);
              return;
            }
            window.location.replace('<?= getUrlByName('web'); ?>');
          })
          .catch((err) => console.error(err))
      });
    });
  });
</script>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>