<fieldset>
  <label>
    <?= __('web.category'); ?> <sup class="red">*</sup>
  </label>
  <input name="facet_select" id="category_id" required>
  <div class="help"><?= __('web.necessarily'); ?>...</div>
</fieldset>

<script nonce="<?= $_SERVER['nonce']; ?>">
  var focus_search = async (props = {}) => {
    var settings = {
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
      const fetchResponse = await fetch('/search/category', settings);
      return await fetchResponse.json();
    } catch (e) {
      return e;
    }
  };

  document.addEventListener("DOMContentLoaded", async () => {

    let search = await focus_search();
    let input = document.querySelector('#category_id');

    let options = {
      skipInvalid: true, // <- не добавлять повтороно не допускаемые теги
      enforceWhitelist: true, // <- добавлять только из белого списка
      dropdown: {
        maxItems: 7, // <- максимум показов фасетов
        classname: "tags-look", // <- пользова. имя класса для этого раскр. списка, чтобы оно могло быть целевым
        enabled: 0, // <- показывать предложения по фокусировке
        closeOnSelect: false // <- не скрывайте раскрывающийся список "Предложения" после выбора элемента
      },
      maxTags: 3, // <- ограничим выбор фасетов
      callbacks: {
        "dropdown:show": async (e) => await focus_search(),
      },
      whitelist: search,
    };

    let tagify = new Tagify(input, options);

    <?php if ($action == 'edit') : ?>
      tagify.addTags(JSON.parse('<?= json_encode($data['category_arr']) ?>'))
    <?php else : ?>
      <?php if (!empty($data['topic'])) : ?>
        <?php if ($data['topic']) :
          $id     = $data['topic']['facet_id'];
          $title  = $data['topic']['facet_title'];
        ?>
          tagify.addTags([{
            value: '<?= $id; ?>',
            facet_title: '<?= $title; ?>'
          }])
        <?php else : ?>
          tagify.addTags([])
        <?php endif; ?>
      <?php else : ?>
        tagify.addTags([])
      <?php endif; ?>
    <?php endif;  ?>
  });
</script>