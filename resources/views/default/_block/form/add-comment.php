<?php use Hleb\Constructor\Handlers\Request; ?>

<div class="cm_addentry max-w780 mt10">
  <?php if (UserData::checkActiveUser()) : ?>
    <form action="<?= url('content.create', ['type' => 'comment']); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>
      <textarea id="qcomment" rows="5" minlength="6" placeholder="<?= __('app.markdown'); ?>..." name="content"></textarea>
      <fieldset>
        <input type="hidden" name="comment_id" id="comment_id" value="<?= Request::getPostInt('comment_id'); ?>">
        <?= Html::sumbit(__('app.reply')); ?>
        <span id="cancel_comment" class="text-sm inline ml5 gray"><?= __('app.cancel'); ?></span>
      </fieldset>
    </form>
  <?php else : ?>
    <textarea rows="5" disabled="disabled" placeholder="<?= __('app.auth_comm'); ?>."></textarea>
    <div>
      <?= Html::sumbit(__('app.reply')); ?>
      <span id="cancel_comment" class="text-sm inline ml5 gray"><?= __('app.cancel'); ?></span>
    </div>
  <?php endif; ?>
</div>