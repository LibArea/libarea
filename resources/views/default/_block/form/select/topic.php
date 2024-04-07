<fieldset>
  <label>
    <?= __('app.topics'); ?> <sup class="red">*</sup>
  </label>

  <?php if ($action == 'edit' || $action == 'add') { ?>
    <input name="facet_select" id="topic_id" required>
  <?php } ?>
  <div class="help"><?= __('app.necessarily'); ?>...</div>
</fieldset>

<script nonce="<?= config('main', 'nonce'); ?>">
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
      const fetchResponse = await fetch('/search/select/topic', settings);
      return await fetchResponse.json();
    } catch (e) {
      return e;
    }
  };

  document.addEventListener("DOMContentLoaded", async () => {

    let search = await focus_search();
    let input = document.querySelector('#topic_id');

    let options = {
      // userInput: false,        // <- отключим пользовательский ввод
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

    <?php if ($action == 'edit') { ?>
       //tagify.addTags([{id:'20', value:'Веб-разработка'},{id:'43', value:'Новости и СМИ'},])
       tagify.addTags(JSON.parse('<?= json_encode($data['topic_arr']) ?>'))
    <?php } else { ?>
      <?php if (!empty($topic)) { ?>
        <?php if ($topic) {
          $id     = $topic['facet_id'];
          $title  = $topic['facet_title'];
        ?>
          tagify.addTags([{
            id: '<?= $id; ?>',
            value: '<?= $title; ?>'
          }])
        <?php } else { ?>
          tagify.addTags([])
        <?php } ?>
      <?php } else { ?>
        tagify.addTags([])
      <?php } ?>
    <?php }  ?>
  });
</script>