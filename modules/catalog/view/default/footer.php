<footer>
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= Translate::get('site.directory'); ?></span>
</footer>

<script src="/assets/js/sweetalert/sweetalert2.all.min.js"></script>
<script src="/assets/js/tippy/popper.min.js"></script>
<script src="/assets/js/tippy/tippy-bundle.umd.min.js"></script>
<script src="/assets/js/admin.js"></script>
<script src="/assets/js/app.js"></script>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
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