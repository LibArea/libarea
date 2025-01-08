<main>
  <div class="box">
    <ul class="nav">
      <li><a href="<?= url('polls'); ?>"><?= __('app.all'); ?></a></li>
      <li class="active"><?= __('app.add_poll'); ?></li>
    </ul>
    <form action="<?= url('add.poll', method: 'post'); ?>" id="myform" method="post">
      <?= $container->csrf()->field(); ?>
      <fieldset class="max-w-md">
        <input type="text" name="title" />
      </fieldset>
      <fieldset class="max-w-sm">
        <p><?= __('app.option'); ?> 1: <input type="text" id="in1" name="1" /></p>
        <p><?= __('app.option'); ?> 2: <input type="text" id="in2" name="2" /></p>
      </fieldset>
      <div class="flex gap items-center">
        <div class="add-el gray-600 text-sm">+ <?= __('app.option'); ?></div>
        <?= Html::sumbit(__('app.add_poll')); ?>
      </div>
    </form>
  </div>
</main>
<aside>
  <div class="box shadow text-sm">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.add_poll'); ?>
  </div>
</aside>

<script nonce="<?= config('main', 'nonce'); ?>">
  let maxFieldLimit = 6;

  queryAll(".add-el").forEach(el => el.addEventListener("click", function(e) {
    addEl();
  }));

  function addEl() {
    let inputs = document.querySelectorAll('input[type="text"]')
    let lastNum = ((inputs[inputs.length - 1]).getAttribute('name'));

    let nextNum = Number(lastNum) + 1;

    if (nextNum >= maxFieldLimit) {
      alert("<?= __('msg.field_limit'); ?> = " + maxFieldLimit);
      return false;
    }

    let elem = document.createElement("p");
    elem.innerHTML = `<?= __('app.option'); ?> ${nextNum}: <input type="text" id="in${nextNum}" name="${nextNum}">`;

    let parentGuest = document.getElementById("in" + lastNum);
    parentGuest.parentNode.insertBefore(elem, parentGuest.nextSibling);
  }
</script>