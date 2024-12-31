<main>
  <?= insert('/content/user/setting/nav'); ?>
  <div class="box">
    <form method="post" action="<?= url('setting.edit.avatar', method: 'post'); ?>" enctype="multipart/form-data">
      <?= $container->csrf()->field(); ?>

      <div class="file-upload" id="file-drag">
        <div class="mb-block">
          <?= Img::avatar($data['user']['avatar'], $data['user']['login'], 'img-xl', 'max'); ?>
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="img-xl">
          <div id="start" class="mt15">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none"><?= __('app.select_image'); ?></div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <fieldset class="clear gray">
        <div class="mb5 text-sm"><?= __('app.recommended_size'); ?>: 240x240px (jpg, jpeg, png, webp)</div>
        <?= Html::sumbit(__('app.download')); ?>
      </fieldset>

      <fieldset class="file-upload" id="file-drag">
        <div class="mb-block">
          <?php if ($data['user']['cover_art'] != 'cover_art.jpeg') : ?>
            <div class="relative mr15">
              <img class="block br-gray max-w-100" src="<?= Img::cover($data['user']['cover_art'], 'user'); ?>">
              <a class="right text-sm" href="<?= url('delete.user.cover', ['id' => $container->user()->id()]); ?>">
                <?= __('app.remove'); ?>
              </a>
            </div>
          <?php else : ?>
            <div class="block br-gray max-w-100 text-sm gray p15">
              <?= __('app.no_cover'); ?>...
            </div>
          <?php endif; ?>
          <div id="start">
            <img id="file-image" class="img-xl br-gray bg-white">
            <input id="file-upload" type="file" name="cover" accept="image/*" />
            <div id="notimage" class="none">Please select an image</div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </fieldset>

      <fieldset>
        <div class="text-sm gray mb15"><?= __('app.recommended_size'); ?>: 1920x240px (jpg, jpeg, png, webp)</div>
        <?= Html::sumbit(__('app.download')); ?>
      </fieldset>
    </form>
  </div>
</main>
<aside>
  <div class="box">
    <?= __('help.avatar_info'); ?>
  </div>
</aside>