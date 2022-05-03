<fieldset>
  <label><?= __('app.section'); ?> <?php if (!empty($red)) { ?><sup class="red">*</sup><?php } ?></label>
  <input name="section_select" id="section_id">
  <?php if (!empty($help)) { ?><div class="help"><?= $help; ?>...</div><?php } ?>
</fieldset>

<script nonce="<?= $_SERVER['nonce']; ?>">
  let section_search = async (props = {}) => {
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
      const fetchResponse = await fetch('/search/section', settings);
      return await fetchResponse.json();
    } catch (e) {
      return e;
    }
  };

  document.addEventListener("DOMContentLoaded", async () => {

    let search_section = await section_search();
    let input = document.querySelector('#section_id');
    let options = {
      tagTextProp: "facet_title",
      mode: "select",
      maxTags: 1,
      callbacks: {
        "dropdown:show": async (e) => await blog_search(),
      },
      whitelist: search_section,
    }

    let tagify = new Tagify(input, options);

    <?php if ($action == 'edit') { ?>
      <?php if (!empty($data['section_arr'])) { ?>
        tagify.addTags(JSON.parse('<?= json_encode($data['section_arr']) ?>'))
      <?php } ?>
    <?php } else { ?>
        tagify.addTags([])
    <?php }  ?>
  });
</script>