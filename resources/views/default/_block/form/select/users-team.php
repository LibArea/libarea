<fieldset>
    <label for="name"><?= __('app.users'); ?></label>
    <input name='user_id' id="user_id">
</fieldset>

<script nonce="<?= config('main', 'nonce'); ?>">
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
            const fetchResponse = await fetch('/search/select/team', settings);
            return await fetchResponse.json();
        } catch (e) {
            return e;
        }
    };

    document.addEventListener("DOMContentLoaded", async () => {
        let search_user = await user_search();
        let input = document.querySelector('#user_id');
        let options = {
            maxTags: 5,
            enforceWhitelist: true, // <- добавлять только из белого списка
            callbacks: {
                "dropdown:show": async (e) => await focus_search(),
            },
            whitelist: search_user,
        };

        tagify = new Tagify(input, options);
        tagify.addTags(JSON.parse('<?= json_encode($users) ?>'))

    });
</script>