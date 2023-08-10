<div class="right inline">
  <div class="relative ml10">
    <span class="trigger gray-600 text-sm">
      <svg class="icons">
        <use xlink:href="/assets/svg/icons.svg#more-horizontal"></use>
      </svg>
    </span>
    <ul class="dropdown">

      <?php if (Access::author('answer', $answer) === true) : ?>
        <li>
          <a class="editansw" href="<?= url('content.edit', ['type' => 'answer', 'id' => $answer['answer_id']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
            <?= __('app.edit'); ?>
          </a>
        </li>
      <?php endif; ?>

      <?php if (UserData::checkAdmin()) : ?>
        <li>
          <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#trash-2"></use>
            </svg>
            <?= $answer['answer_is_deleted'] == 1 ? __('app.recover') : __('app.remove'); ?>
          </a>
        </li>

        <li>
          <a href="<?= url('admin.logip', ['ip' => $answer['answer_ip']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#info"></use>
            </svg>
            <?= $answer['answer_ip']; ?>
          </a>
        <li>
        <?php endif; ?>

        <?php if (UserData::getUserId() != $answer['answer_user_id'] && UserData::getRegType(config('trust-levels.tl_add_report'))) : ?>
        <li>
          <a data-post_id="<?= $post['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" data-a11y-dialog-show="my-dialog">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#alert-circle"></use>
            </svg>
            <?= __('app.report'); ?>
          </a>
        </li>
      <?php endif; ?>

      <?php if ($post['amount_content'] > 1) : ?>
        <?php if (UserData::getUserId() == $post['post_user_id'] || UserData::checkAdmin()) : ?>
          <li>
            <a id="best_<?= $answer['answer_id']; ?>" data-id="<?= $answer['answer_id']; ?>" class="answer-best">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#award"></use>
              </svg>
              <?= __('app.raise_answer'); ?>
            </a>
          </li>
        <?php endif; ?>
      <?php endif; ?>

      <li>
        <?= Html::favorite($answer['answer_id'], 'answer', $answer['tid'], 'heading'); ?>
      </li>

      <li>
        <a rel="nofollow" class="gray-600" href="<?= $post_url; ?>#answer_<?= $answer['answer_id']; ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#anchor"></use>
          </svg>
          <?= __('app.link'); ?>
        </a>
      </li>

    </ul>
  </div>
</div>