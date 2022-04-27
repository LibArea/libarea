<?php if (UserData::checkAdmin()) { ?>
  <fieldset>
    <label><?= __('author'); ?></label>
    <input name='user_id' id="user_id">
  </fieldset>

    <script nonce="<?= $_SERVER['nonce']; ?>">
        const user_search = async (props = {}) => {
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
                const fetchResponse = await fetch('/search/user', settings);
                return await fetchResponse.json();
            } catch (e) {
                return e;
            }
        };

        document.addEventListener("DOMContentLoaded", async () => {
            let search_user = await user_search();
            let input = document.querySelector('#user_id');
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
            tagify.addTags([{
                id: '<?= UserData::getUserId(); ?>',
                value: '<?= UserData::getUserLogin(); ?>'
            }])

        });
    </script>
<?php } ?>