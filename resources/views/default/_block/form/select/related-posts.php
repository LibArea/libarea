<?
// Связанные посты
?>

<div class="mb20 max-w640">
  <label class="block mb5">
    <?= $title; ?>
  </label>
  <input name="post_select[]" id="post_id">
</div>

<script nonce="<?= $_SERVER['nonce']; ?>">
  var post_search = async (props = {}) => {
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
      const fetchResponse = await fetch('/search/post', settings);
      return await fetchResponse.json();
    } catch (e) {
      return e;
    }
  };

  document.addEventListener("DOMContentLoaded", async () => {

    var search_post = await post_search();
    var input = document.querySelector('#post_id');
    var options_post = {
      tagTextProp: "post_title",
      // userInput: false,        // <- отключим пользовательский ввод
      skipInvalid: true, // <- не добавлять повтороно не допускаемые теги
      enforceWhitelist: true, // <- добавлять только из белого списка
      maxTags: 3, // <- ограничим выбор фасетов
      callbacks: {
        "dropdown:show": async (e) => await post_search(),
      },

      whitelist: search_post,
    };

    var tagify_post = new Tagify(input, options_post);

    <?php if ($action == 'edit') {   ?>
      tagify_post.addTags(JSON.parse('<?= json_encode($data['post_arr']) ?>'))
    <?php } ?>

  });
</script>