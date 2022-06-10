<?php $blog = $data['facet'];
if ($blog['facet_is_deleted'] == 0) : ?>
  <div class="w-100">
    <div class="box-flex" style="background-image: linear-gradient(to right, white 0%, transparent 60%), url(<?= Html::coverUrl($blog['facet_cover_art'], 'blog'); ?>); background-position: 50% 50%;">
      <?= Html::image($blog['facet_img'], $blog['facet_title'], 'img-xl mr15', 'logo', 'max'); ?>
      <div class="mb-ml0 flex-auto">
        <h1 class="mb0 mt10 text-2xl">
          <?php if (UserData::checkAdmin() || $blog['facet_user_id'] == UserData::getUserId()) : ?>
            <a class="right white fon-rgba" href="<?= url('content.edit', ['type' => 'blog', 'id' => $blog['facet_id']]); ?>">
              <i class="bi-pencil bold"></i>
            </a>
          <?php endif; ?>
          <?= $blog['facet_seo_title']; ?>
        </h1>
        <div class="text-sm mt10 mb-none"><?= $blog['facet_short_description']; ?></div>
        <div class="right">
          <?= Html::signed([
            'type'            => 'facet',
            'id'              => $blog['facet_id'],
            'content_user_id' => $blog['facet_user_id'],
            'state'           => is_array($data['facet_signed']),
          ]); ?>
        </div>
      </div>
    </div>

    <div class="flex gap">
      <main>
        <?= insert('/content/post/post', ['data' => $data]); ?>
        <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url('blog', ['slug' => $blog['facet_slug']])); ?>
      </main>
      <aside>
        <?php if ($blog['facet_is_deleted'] == 0) : ?>
          <div class="box bg-beige">
            <h3 class="uppercase-box"><?= __('app.created_by'); ?></h3>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= url('profile', ['login' => $data['user']['login']]); ?>">
              <?= Html::image($data['user']['avatar'], $data['user']['login'], 'img-base', 'avatar', 'small'); ?>
              <span class="ml5"><?= $data['user']['login']; ?></span>
            </a>
            <div class="gray-600 text-sm mt5">
              <i class="bi-calendar-week mr5 ml5 middle"></i>
              <span class="middle lowercase"><?= Html::langDate($blog['facet_add_date']); ?></span>
            </div>
            <div class="relative max-w300 mt15">
              <?= insert('/_block/facet/focus-users', [
                'topic_focus_count' => $blog['facet_focus_count'],
                'focus_users'       => $data['focus_users'] ?? '',
              ]); ?>
              <div class="content_<?= $blog['facet_id']; ?> absolute bg-white box-shadow-all right0"></div>
            </div>
          </div>
          <?php if ($data['info']) : ?>
            <div class="box bg-lightgray shown_post">
              <?= $data['info']; ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </aside>
    </div>
  </div>
<?php else : ?>
  <div class="center">
    <i class="bi-x-octagon text-8xl"></i>
    <div class="mt5 gray"><?= __('app.remote'); ?></div>
  </div>
<?php endif; ?>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.querySelectorAll(".focus-user")
    .forEach(el => el.addEventListener("click", function(e) {
      let content = document.querySelector('.content_<?= $blog['facet_id']; ?>');
      let div = document.querySelector(".content_<?= $blog['facet_id']; ?>");
      div.classList.remove("none");
      fetch("/topic/<?= $blog['facet_slug']; ?>/followers/<?= $blog['facet_id']; ?>", {
          method: "POST",
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        })
        .then(
          response => {
            return response.text();
          }
        ).then(
          text => {
            content.innerHTML = text;
          }
        );
      window.addEventListener('mouseup', e => {
        div.classList.add("none");
      });
    }));
</script>