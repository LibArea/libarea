<?php $blog = $data['facet'];
if ($blog['facet_is_deleted'] == 0) { ?>
  <div class="w-100">
    <div class="box-flex-white" style="background-image: linear-gradient(to right, white 0%, transparent 60%), url(<?= cover_url($blog['facet_cover_art'], 'blog'); ?>); background-position: 50% 50%;">
      <div class="mb-none">
        <?= facet_logo_img($blog['facet_img'], 'max', $blog['facet_title'], 'img-xl'); ?>
      </div>
      <div class="mb-ml0 flex-auto">
        <h1 class="mb0 mt10 text-2xl">
          <?= $blog['facet_seo_title']; ?>
          <?php if (UserData::checkAdmin() || $blog['facet_user_id'] == $user['id']) { ?>
            <a class="right white fon-rgba -mt20" href="<?= getUrlByName('content.edit', ['type' => 'blog', 'id' => $blog['facet_id']]); ?>">
              <i class="bi-pencil bold"></i>
            </a>
          <?php } ?>
        </h1>
        <div class="text-sm"><?= $blog['facet_short_description']; ?></div>

        <div class="mt15 right">
          <?= Tpl::import('/_block/facet/signed', [
            'user'           => $user,
            'topic'         => $blog,
            'topic_signed'  => is_array($data['facet_signed']),
          ]); ?>
        </div>

        <div class="relative max-w300">
          <?= Tpl::import('/_block/facet/focus-users', [
            'topic_focus_count' => $blog['facet_focus_count'],
            'focus_users'       => $data['focus_users'] ?? '',
          ]); ?>
          <div class="content_<?= $blog['facet_id']; ?> absolute bg-white box-shadow-all z-10 right0"></div>
        </div>
      </div>
    </div>

    <div class="flex gap">
      <main class="col-two">
        <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('blog', ['slug' => $blog['facet_slug']])); ?>
      </main>
      <aside>
        <?php if ($blog['facet_is_deleted'] == 0) { ?>
          <div class="box-white text-sm">
            <h3 class="uppercase-box"><?= Translate::get('created by'); ?></h3>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('profile', ['login' => $data['user']['login']]); ?>">
              <?= user_avatar_img($data['user']['avatar'], 'max', $data['user']['login'], 'ava-base'); ?>
              <span class="ml5"><?= $data['user']['login']; ?></span>
            </a>
            <div class="gray-400 text-sm mt5">
              <i class="bi-calendar-week mr5 ml5 middle"></i>
              <span class="middle lowercase"><?= $blog['facet_add_date']; ?></span>
            </div>
          </div>
          <?php if ($data['info']) { ?>
            <div class="box-white text-sm shown_post">
              <?= $data['info']; ?>
            </div>
          <?php } ?>

          <?php if (!empty($data['pages'])) { ?>
            <div class="sticky top0 top-sm">
              <div class="box-white text-sm">
                <h3 class="uppercase-box"><?= Translate::get('pages'); ?></h3>
                <?php foreach ($data['pages'] as $ind => $row) { ?>
                  <div class="mb5">
                    <a class="relative pt5 pb5 hidden" href="<?= getUrlByName('blog.article', ['slug' => $blog['facet_slug'], 'post_slug' => $row['post_slug']]); ?>">
                      <?= $row['post_title']; ?>
                    </a>
                    <?php if (UserData::checkAdmin() || $blog['facet_user_id'] == $user['id']) { ?>
                      <a class="text-sm gray-400" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('content.edit', ['type' => 'page', 'id' => $row['post_id']]); ?>">
                        <i class="bi-pencil"></i>
                      </a>
                    <?php } ?>
                  </div>
                <?php } ?>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </aside>
    </div>
  </div>
<?php } else { ?>
  <div class="center">
    <i class="bi-x-octagon text-8xl"></i>
    <div class="mt5 gray"><?= Translate::get('remote'); ?></div>
  </div>
<?php } ?>

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