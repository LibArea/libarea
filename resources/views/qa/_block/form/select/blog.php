<div class="mb20 max-w640">
  <label class="block">
    <?= Translate::get('blog'); ?>
  </label>
  <input name="blog_select" id="blog_id">
</div>

<script nonce="<?= $_SERVER['nonce']; ?>">
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
      const fetchResponse = await fetch('/search/blog', settings);
      return await fetchResponse.json();
    } catch (e) {
      return e;
    }
  };

  document.addEventListener("DOMContentLoaded", async () => {

    let search_blog = await blog_search();
    let input = document.querySelector('#blog_id');
    let options = {
      tagTextProp: "facet_title",
      mode: "select",
      maxTags: 1,
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
      <?php if (!empty($data['facets']['blog'])) {  ?>
        <?php $id = $data['facets']['blog']['facet_id'];
        $title = $data['facets']['blog']['facet_title'];
        ?>
        tagify.addTags([{
          value: '<?= $id; ?>',
          facet_title: '<?= $title; ?>'
        }])
      <?php } else { ?>
        tagify.addTags([])
      <?php }  ?>
    <?php }  ?>
  });
</script>