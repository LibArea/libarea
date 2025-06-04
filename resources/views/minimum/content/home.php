<main>
  <?= insert('/content/publications/choice', ['data' => $data]); ?>
  <?php if ($container->user()->scroll()) : ?>
    <div id="scrollArea"></div>
    <div id="scroll"></div>
  <?php else : ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null); ?>
  <?php endif; ?>
</main>