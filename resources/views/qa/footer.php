</div>
<a class="up_down_btn fixed bg-gray-100 none w30 h30 z-50 br-rd3 center gray" title="<?= Translate::get('up'); ?>">&uarr;</a>

<script src="/assets/js/common.js"></script>
<script src="/assets/js/sweetalert/sweetalert2.all.min.js"></script>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if ($uid['user_id'] == 0) { ?>
    document.querySelectorAll(".click-no-auth")
      .forEach(el => el.addEventListener("click", function(e) {
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
      }));
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
</script>

</body>

</html>