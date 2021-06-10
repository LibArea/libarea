</div>
<div class="v-ots clear">
    <br>
    <center>
        <small>
             &copy; <?= date('Y'); ?>  <a href="https://loriup.ru">Loriup.ru</a>  
        </small>
    <center>
</div>
<script src="/assets/js/admin.js"></script>
<script src="/assets/js/sweetalert2.min.js"></script> 
<link rel="stylesheet" href="/assets/css/sweetalert2.min.css">
<?php if ($uid['msg']) { ?>
    <?php foreach($uid['msg'] as $message) { ?>
        <script nonce="<?= $_SERVER['nonce']; ?>">
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
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
        </script>
    <?php } ?>
<?php } ?>
</body>
</html>