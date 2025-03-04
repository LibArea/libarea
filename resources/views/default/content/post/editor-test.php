<main>
  <div class="box">
    <h2 class="title"><?= __('app.development'); ?></h2>
	<br><br>
	<div id="root"></div>
  </div>
</main>
<aside>
  <div class="box">
    <h4 class="uppercase-box"><?= __('app.development'); ?></h4>
    <?= __('app.being_developed'); ?> https://github.com/BearToCode/carta/issues/37
  </div>
</aside>



  <link rel="stylesheet" href="/assets/js/editor-test/carta.css" />



  <script type="module" nonce="<?= config('main', 'nonce'); ?>">
    import CartaMd           from '/assets/js/editor-test/carta-md.js'
    import plugin_tikz       from '/assets/js/editor-test/plugin-tikz.js'
    import plugin_slash      from '/assets/js/editor-test/plugin-slash.js'
    import plugin_emoji      from '/assets/js/editor-test/plugin-emoji.js'
    import plugin_code       from '/assets/js/editor-test/plugin-code.js'
    import plugin_anchor     from '/assets/js/editor-test/plugin-anchor.js'
    import plugin_attachment from '/assets/js/editor-test/plugin-attachment.js'

    const init = (target) => {
      const carta = new CartaMd.Carta({
        extensions: [
          plugin_tikz(),
          plugin_slash(),
          plugin_emoji(),
          plugin_code(),
          plugin_anchor(),
          plugin_attachment({
            supportedMimeTypes: ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'],
            upload: async (file) => `https://beartocode.github.io/carta/${file.name}`
          })
        ]
      })

      CartaMd.MarkdownEditor({
        target,
        carta,
        context: []
      })
    }

    document.addEventListener('DOMContentLoaded', (event) => {
      init(
        document.getElementById('root')
      )
    })
  </script>

<!--link rel="stylesheet" href="/assets/js/editor-test/index.css">
<script src="/assets/js/editor-test/index.min.js"></script>
<script nonce="<?= config('main', 'nonce'); ?>">
new Vditor('vditor', {
  cdn: "/assets/js/editor-test/",
   mode: "wysiwyg",
})

</script-->


 