<script src="/assets/js/common.js"></script>
<script src="/assets/js/medium-zoom.js"></script>
<script src="/assets/js/prism.js"></script>

<?php if (UserData::checkActiveUser()) : ?><script src="/assets/js/app.js"></script><?php endif; ?>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    mediumZoom(document.querySelectorAll('.img-preview img'));
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
            let notif = document.getElementById('notif');
            let number = notif.querySelector('.number');

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
    document.querySelectorAll(".click-no-auth")
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
    const coolDiv = document.getElementById("scroll");

    // Куда будем подгружать
    const scroll = document.getElementById("scrollArea");

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

</body>

</html>