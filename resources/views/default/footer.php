</div>
<a class="up_down_btn none w30 h30 z-50 br-rd3 center gray" title="<?= Translate::get('up'); ?>">&uarr;</a>

<script async src="/assets/js/common.js"></script>
<script src="/assets/js/layer/layer.js"></script>

<?php if ($msg = getMsg()) { ?>
  <?php foreach ($msg as $message) { ?>
    <script nonce="<?= $_SERVER['nonce']; ?>">
      layer.msg('<?= $message[0]; ?>', {
        icon: <?= $message[1]; ?>,
        time: 3000,
        skin: 'layui-layer-molv',
      });
    </script>
  <?php } ?>
<?php } ?>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

</body>
<script nonce="<?= $_SERVER['nonce']; ?>">
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
              html += '<a class="blue block size-14 mb15 mr10" href="/topic/' + data.topic_slug + '">';
              html += '<img class="w21 mr5 br-box-grey" src="<?= AG_PATH_TOPICS_LOGOS; ?>' + data.topic_img + '">';
              html += data.topic_title + '</a>';  
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