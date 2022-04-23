<a class="up_down_btn none mb-none" title="<?= __('up'); ?>">&uarr;</a>

<script src="/assets/js/common.js"></script>
<script src="/assets/js/notiflix/notiflix-aio-3.2.5.min.js"></script>
<?php if ($uid) : ?><script src="/assets/js/app.js"></script><?php endif; ?>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if (!$uid) : ?>
    document.querySelectorAll(".click-no-auth")
      .forEach(el => el.addEventListener("click", function(e) {
        Notiflix.Report.info(
          '<?= __('need.to.login'); ?>',
          '<?= __('login.info'); ?>',
          '<?= __('well'); ?>',
        );
      }));
  <?php endif; ?>
  
  <?= Html::getMsg(); ?>

  <?php if ($scroll) : ?>
    // Что будет смотреть
    const coolDiv = document.getElementById("scroll");

    // Куда будем подгружать
    const scroll = document.getElementById("scrollArea");

    // Начальная загрузка (первая страница загружается статически)
    let postPage = 2;

    function getPosts(path) {
      fetch(path)
        .then(res => res.text()).then((res) => {
          scroll.insertAdjacentHTML("beforeend", res);
          postPage += 1;
        })
        .catch((e) => alert(e));
    }

    const observer = new IntersectionObserver(entries => {
      const firstEntry = entries[0];
      if (firstEntry.isIntersecting) {
        if (`${postPage}` > 25) return;
        getPosts(`/post/scroll/${postPage}.html`);
      }
    });
    if (coolDiv) {
      observer.observe(coolDiv);
    }
  <?php endif; ?>
</script>

</body>

</html>