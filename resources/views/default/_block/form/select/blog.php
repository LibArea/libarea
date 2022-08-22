<fieldset>
  <label><?= __('app.blog'); ?> <?php if (!empty($red)) { ?><sup class="red">*</sup><?php } ?></label>
  <input name="blog_select" id="blog_id">
</fieldset>

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
          id: '<?= $id; ?>',
          value: '<?= $title; ?>'
        }])
      <?php } else { ?>
        tagify.addTags([])
      <?php } ?>
    <?php } ?>
  });
</script>