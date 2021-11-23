</div>
<a class="up_down_btn fixed bg-gray-100 none w30 h30 z-50 br-rd3 center gray" title="<?= Translate::get('up'); ?>">&uarr;</a>

</body>

<script async src="/assets/js/common.js"></script>
<script src="/assets/js/sweetalert2.all.min.js"></script>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if ($uid['user_id'] == 0) { ?>
      $(document).on('click', '.click-no-auth', function() {
        const Toast = Swal.mixin({
          toast: true,
          position: 'center',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })
        Toast.fire({
          icon: 'warning',
          title: '<?= Translate::get('you need to log in'); ?>'
        })
      });
  <?php } ?>

  <?php if ($msg = getMsg()) { ?>
    <?php foreach ($msg as $message) { ?>
      const Toast = Swal.mixin({
        toast: true,
        position: 'center',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      Toast.fire({
        icon: '<?= $message[1]; ?>',
        title: '<?= $message[0]; ?>'
      })
    <?php } ?>
  <?php } ?>

  $(document).ready(function() {
    $("#find").keyup(function() {
      fetch_search();
    });
  });

  function fetch_search() {
    var val = document.getElementById("find").value;
    var token = $('input[name="token"]').attr('value');
    if (val.length < 3) {
      return;
    }
    $.ajax({
      type: 'post',
      url: '/api-search',
      data: {
        q: val,
        _token: token
      },
      success: function(response) {
        var data = JSON.parse(response);
        if (data.length > 0) {
          var html = '<div class="flex">';
          $.each(data, function(index, data) {
            if (data.topic_slug) {
              html += '<a class="blue block size-14 mb15 mr10" href="/topic/' + data.facet_slug + '">';
              html += '<img class="w21 mr5 br-box-gray" src="<?= AG_PATH_FACETS_LOGOS; ?>' + data.facet_img + '">';
              html += data.facet_title + '</a>';
            }
            if (data.post_id) {
              html += '<a class="block black size-14 mb10" href="/post/' + data.post_id + '">' +
                data.title + '</a>';
            }
            html += '</div>';
          });
        } else {
          var html = "<span class='size-14 gray'><?= Translate::get('no results'); ?></span>";
        }
        document.getElementById("search_items").classList.add("block");
        document.getElementById("search_items").innerHTML = html;
        var menu = document.querySelector('.none.block');
        if (menu) {
          document.onclick = function(e) {
            if (event.target.className != '.none.block') {
              document.getElementById("search_items").classList.remove("block");
            };
          };
        }
      }
    });
  }
</script>

</html>