</div>
<footer class="w-100 mt15 p15 bg-zinc-800 clear">
  <div class="col-span-12 max-width grid grid-cols-12 mr-auto">
    <div class="text-sm mt5 mb5 gray-400 col-span-3">
      <?= Config::get('meta.name'); ?>
      &copy; <?= date('Y'); ?> — <?= Translate::get('community'); ?>
    </div>
  </div>
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