</div>
<footer class="footer">
  <div class="wrap">
    <div class="text-sm right">
      <a class="gray-600 mr15" href="<?= url('search'); ?>"><?= __('app.search'); ?></a>
      <a class="gray-600 mr15" href="<?= url('blogs.all'); ?>"><?= __('app.blogs'); ?></a>
      <a class="gray-600 mr15" href="<?= url('topics.all'); ?>"><?= __('app.topics'); ?></a>
      <a class="gray-600" href="/info/article/information"><?= __('app.information'); ?></a>
    </div>
  </div>
</footer>

<?= insert('/scripts', ['sheet' => $data['sheet'] ?? false]); ?>