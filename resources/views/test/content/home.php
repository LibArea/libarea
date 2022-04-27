<main class="w-100">
  <?= Tpl::insert('/content/post/post', ['data' => $data]); ?>
  <?php if (UserData::getUserScroll()) : ?>
    <div id="scrollArea"></div>
    <div id="scroll"></div>
  <?php else : ?>
    <div class="mb15">
      <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
    </div>
  <?php endif; ?>
</main>