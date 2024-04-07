  <fieldset>
    <label><?= __('app.poll'); ?></label>
    <input name='poll_id' id="poll_id">
  </fieldset>

<script nonce="<?= config('main', 'nonce'); ?>">
    const poll_search = async (props = {}) => {
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
            const fetchResponse = await fetch('/search/select/poll', settings);
            return await fetchResponse.json();
        } catch (e) {
            return e;
        }
    };

    document.addEventListener("DOMContentLoaded", async () => {
        let search_user = await poll_search();
        let input = document.querySelector('#poll_id');
        let options = {
            mode: "select",
            maxTags: 1,
            enforceWhitelist: true, // <- добавлять только из белого списка
            callbacks: {
                "dropdown:show": async (e) => await focus_search(),
            },
            whitelist: search_user,
        };

        tagify = new Tagify(input, options);
        
        <?php if (!empty($poll)) { ?>
          tagify.addTags([{
            id: '<?= $poll['poll_id']; ?>',
            value: '<?= $poll['poll_title']; ?>'
          }])
        <?php } ?>
    });
</script>