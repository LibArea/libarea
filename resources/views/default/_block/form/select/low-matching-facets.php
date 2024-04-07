<fieldset>
  <label><?= $title; ?></label>
  <input name="facet_matching" id="facet_id_matching">
</fieldset>

<script nonce="<?= config('main', 'nonce'); ?>">
  var facet_search = async (props = {}) => {
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
      const fetchResponse = await fetch('/search/select/<?= $type; ?>', settings);
      return await fetchResponse.json();
    } catch (e) {
      return e;
    }
  };

  document.addEventListener("DOMContentLoaded", async () => {
    var search_facet = await facet_search();
    var input = document.querySelector('#facet_id_matching');
    var options_post = {
      // userInput: false,        // <- отключим пользовательский ввод
      skipInvalid: true, // <- не добавлять повтороно не допускаемые теги
      enforceWhitelist: true, // <- добавлять только из белого списка
      maxTags: 3, // <- ограничим выбор фасетов
      callbacks: {
        "dropdown:show": async (e) => await facet_search(),
      },
      whitelist: search_facet,
    };

    var tagify_post = new Tagify(input, options_post);

    <?php if ($action == 'edit') { ?>
      tagify_post.addTags(JSON.parse('<?= json_encode($data['low_matching']) ?>'))
    <?php } ?>
  });
</script>