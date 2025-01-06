<script src="/assets/js/common.js?<?= config('general', 'version'); ?>"></script>
<script src="/assets/js/zooom.js?<?= config('general', 'version'); ?>"></script>
<script src="/assets/js/prism/prism.js?<?= config('general', 'version'); ?>"></script>

<?php if (UserData::checkActiveUser()) : ?>
  <script src="/assets/js/app.js?<?= config('general', 'version'); ?>"></script>
  <?= insert('/device-id'); ?>
<?php endif; ?>


<script nonce="<?= config('main', 'nonce'); ?>">
  document.addEventListener('DOMContentLoaded', () => {
    mediumZoom(queryAll('.img-preview img'));
  });
  <?php if (UserData::checkActiveUser()) : ?>
    const update_time = <?= config('general.notif_update_time'); ?>;

    function load_notification() {
      fetch("/notif", {
          method: "POST",
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        })
        .then(function(response) {
          if (!response.ok) {
            return Promise.reject(new Error(
              'Response failed: ' + response.status + ' (' + response.statusText + ')'
            ));
          }
          return response.json();
        }).then(function(data) {
          if (data != false) {
            let notif = getById('notif');
            let number = notif.querySelector('.number-notif');

            notif.firstElementChild.classList.add("active");

            number.classList.add("show");
            number.innerHTML = data.length;
          }
        }).catch(function(error) {
          // error
        });
    }
    setInterval(function() {
      load_notification();
    }, update_time);
  <?php else : ?>
    queryAll(".click-no-auth")
      .forEach(el => el.addEventListener("click", function(e) {
        Notice('<?= __('app.need_login'); ?>', 3500, {
          valign: 'bottom',
          align: 'center'
        });
      }));
  <?php endif; ?>

  <?= Msg::get(); ?>

  <?php if (UserData::getUserScroll()) : ?>
    // Что будет смотреть
    const coolDiv = getById("scroll");

    // Куда будем подгружать
    const scroll = getById("scrollArea");

    // Начальная загрузка (первая страница загружается статически)
    let postPage = 2;

    var type = 'no';
    <?php $sheet = $sheet ?? null;
    if ($sheet == 'all') : ?>
      var type = 'all';
    <?php endif; ?>

    function getPosts(path) {
      fetch(path)
        .then(res => res.text()).then((res) => {
          if (getById("no_content")) {
            return;
          }
          scroll.insertAdjacentHTML("beforeend", res);
          postPage += 1;
        })
        .catch((e) => alert(e));
    }

    const observer = new IntersectionObserver(entries => {
      const firstEntry = entries[0];
      if (firstEntry.isIntersecting) {
        if (`${postPage}` > 25) return;
        getPosts(`/post/scroll/${type}?page=${postPage}`);
      }
    });
    if (coolDiv) {
      observer.observe(coolDiv);
    }
  <?php endif; ?>
</script>

<?= insert('/metrika'); ?>

</body>

</html>