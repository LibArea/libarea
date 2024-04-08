<?
// Searching for posts to move comments
// Поиск постов для переноса комментариев
?>
<fieldset>
  <label><?= __('app.posts'); ?></label>
  <input name="post_select" id="post_id">
</fieldset>

<script nonce="<?= config('main', 'nonce'); ?>">
 document.addEventListener("DOMContentLoaded", async () => {
  var input = document.querySelector('#post_id');   
  let tagify_post = new Tagify(input, {
    pattern: /.{1,}/,
   // userInput: false, // <- отключим пользовательский ввод
    skipInvalid: true, // <- не добавлять повтороно не допускаемые теги
    enforceWhitelist: true, // <- добавлять только из белого списка
    tagTextProp: "post_title",
    maxTags: 1, // <- ограничим выбор постов
   });
 
  let abortCtrl; // за прерывание вызова
  tagify_post.on('input', e => {
    const term = e.detail.value.trim();
    if (term.length < 3) return;
    tagify_post.settings.whitelist.length = 0; // сбросим белый список
    abortCtrl && abortCtrl.abort();
    abortCtrl = new AbortController();
    // покажем анимацию загрузки и скроем раскрывающийся список предложений
    tagify_post.loading(true).dropdown.hide.call(tagify_post);
    
    fetch("/search/select/post", {
      method: "POST",
      body: "q=" + encodeURIComponent(term),
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(r => r.json())
      .then((list) => {
        tagify_post.settings.whitelist.splice(0, list.length, ...list); // обновим массив бел. список на месте
        tagify_post.loading(false).dropdown.show.call(tagify_post, term); // отобразим раскрывающийся список предложений
      });
  });
 });
</script>