<fieldset>
  <label><?= __('app.blog'); ?> <?php if (!empty($red)) { ?><sup class="red">*</sup><?php } ?></label>
  <input name="blog_select" id="blog_id">
</fieldset>

<script nonce="<?= config('main', 'nonce'); ?>">
  let blog_search = async (props = {}) => {
    const settings = {
      method: 'POST',
      mode: 'cors',
      cache: 'no-cache',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json'
      },
      redirect: 'follow',
      referrerPolicy: 'no-referrer',
      body: JSON.stringify(props)
    };
    try {
      const fetchResponse = await fetch('/search/select/blog', settings);
      return await fetchResponse.json();
    } catch (e) {
      return e;
    }
  };

  document.addEventListener("DOMContentLoaded", async () => {

    let search_blog = await blog_search();
    let input = document.querySelector('#blog_id');
    let options = {
      maxTags: 1,
      dropdown: {
        maxItems: 7, // <- максимум показов фасетов
        classname: "tags-look", // <- пользова. имя класса для этого раскр. списка, чтобы оно могло быть целевым
        enabled: 0, // <- показывать предложения по фокусировке
        closeOnSelect: false // <- не скрывайте раскрывающийся список "Предложения" после выбора элемента
      },
      callbacks: {
        "dropdown:show": async (e) => await blog_search(),
      },
      whitelist: search_blog,
    }

    let tagify = new Tagify(input, options);

    <?php if ($action == 'edit') { ?>
      <?php if (!empty($data['blog_arr'])) { ?>
        tagify.addTags(JSON.parse('<?= json_encode($data['blog_arr']) ?>'))
      <?php } ?>
    <?php } else { ?>
      <?php if (!empty($blog)) {  ?>
        <?php $id = $blog['facet_id'];
        $title = $blog['facet_title'];
        ?>
        tagify.addTags([{
          id: '<?= $id; ?>',
          value: '<?= $title; ?>'
        }])
      <?php } else { ?>
        tagify.addTags([])
      <?php } ?>
    <?php } ?>
  });
</script>