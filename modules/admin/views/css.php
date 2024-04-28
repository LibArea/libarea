<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>

<h4><?= __('admin.topics'); ?>:</h4>
<a href="#" class="tag-grey">.tag-grey</a>
<a href="#" class="tag-violet">.tag-violet</a>
<a href="#" class="tag-yellow">.tag-yellow</a>
<a href="#" class="tag-clear">.tag-clear</a>

<h4 class="mt15"><?= __('admin.buttons'); ?>:</h4>
<p><i class="btn btn-outline-primary">btn btn-outline-primary</i></p>
<p><i class="btn btn-small btn-outline-primary">btn btn-small btn-outline-primary</i></p>
<p><i class="btn btn-primary">btn btn-primary</i></p>
<p><i class="btn btn-small btn-primary">btn btn-small btn-primary</i></p>

<h4><?= __('admin.other'); ?>:</h4>
<div class="box-flex flex-wrap gap">
  <div class="bg-green img-sm"></div>.img-sm
  <div class="bg-green img-base"></div>.img-base
  <div class="bg-green img-lg"></div>.img-lg
  <div class="bg-green img-xl"></div>.img-xl
</div>

<div class="box-flex flex-wrap gap">
  <div class="label label-grey">.label .label-grey</div>
  <div class="label label-green">.label .label-green</div>
  <div class="label label-orange">.label .label-orange</div>
  <div class="label label-teal">.label .label-teal</div>
</div>

<div class="box-flex flex-wrap gap">
  <div class="box">.box</div>
  <div class="box bg-yellow">.box .bg-yellow</div>
  <div class="box bg-violet">.box .bg-violet</div>
  <div class="box bg-lightgray">.box .bg-lightgray</div>
  <div class="box bg-beige">.box .bg-beige</div>
  <div class="box bg-red-200">.box .bg-red-200</div>
  <div class="box bg-green white">.box .bg-green</div>
  <div class="box bg-black white">.box .bg-black</div>
  <div class="box bg-blue white">.box .bg-blue</div>
</div>

<div class="box-flex flex-wrap gap">
  <div class="box border-lightgray">.box .border-lightgray</div>
  <div class="label border-lightgray">.label .border-lightgray</div>
</div>

<h4><?= __('admin.icons'); ?>:</h4>
<div class="flex flex-wrap gap">
  <?php foreach ($data['lists'] as $topic) : ?>
    <div class="center box">
      <svg class="icons">
        <use xlink:href="/assets/svg/icons.svg#<?= $topic; ?>"></use>
      </svg>
      <div class="gray-600"><?= $topic; ?></div>
    </div>
  <?php endforeach; ?>
</div>

<pre><code>&lt;svg class="icons"&gt;
   &lt;use xlink:href="/assets/svg/icons.svg#ID"&gt;&lt;/use&gt;
&lt;/svg&gt;</code></pre>

<li><a rel="nofollow noopener" href="https://feathericons.com/">https://feathericons.com/</a></li>
<li><a rel="nofollow noopener" href="https://tabler-icons.io/">https://tabler-icons.io/</a></li>

</main>

<?= insertTemplate('footer'); ?>