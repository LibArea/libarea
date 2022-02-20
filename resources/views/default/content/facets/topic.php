<?= Tpl::import('/content/menu', ['data' => $data, 'user' => $user]); ?>

<?php $topic = $data['facet']; ?>
<main class="col-span-7 mb-col-12">
  <?php if ($topic['facet_is_deleted'] == 0) { ?>
    <?= Tpl::import('/content/facets/topic-header', ['topic' => $topic, 'user' => $user, 'data' => $data]); ?>
    <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('topic', ['slug' => $topic['facet_slug']])); ?>

  <?php } else { ?>
    <div class="center col-span-10">
      <i class="bi bi-x-octagon text-8xl"></i>
      <div class="mt5 gray"><?= Translate::get('remote'); ?></div>
    </div>
  <?php } ?>
</main>
<aside class="col-span-3 mb-none">
  <?php if ($topic['facet_is_deleted'] == 0) { ?>
    <div class="box-flex-white">
      <div class="center">
        <div class="uppercase text-sm gray-400"><?= Translate::get('posts'); ?></div>
        <?= $topic['facet_count']; ?>
      </div>
      <div class="center relative">
        <div class="uppercase text-sm gray-400"><?= Translate::get('reads'); ?></div>
        <div class="focus-user sky-500">
          <?= $topic['facet_focus_count']; ?>
        </div>
        <div class="content_<?= $topic['facet_id']; ?> absolute bg-white box-shadow-all z-10 right0"></div>
      </div>
    </div>

    <?php if (!empty($data['pages'])) { ?>
      <div class="sticky top0 top-sm">
        <div class="box-white text-sm">
          <h3 class="uppercase-box"><?= Translate::get('pages'); ?></h3>
          <?php foreach ($data['pages'] as $ind => $row) { ?>
            <a class="flex pb10 items-center gray-600" href="">
              <?= $row['post_title']; ?>
            </a>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

    <?= Tpl::import('/_block/sidebar/topic', ['data' => $data]); ?>
    <?php if (!empty($data['writers'])) { ?>
      <div class="sticky top0 top-sm">
        <div class="box-white text-sm">
          <h3 class="uppercase-box"><?= Translate::get('writers'); ?></h3>
          <ul>
          <?php foreach ($data['writers'] as $ind => $row) { ?>
            <li class="mb10">
              <a class="gray-600" href="<?= getUrlByName('profile', ['login' => $row['login']]); ?>">
                <?= user_avatar_img($row['avatar'], 'max', $row['login'], 'ava-sm'); ?>
                <?= $row['login']; ?> (<?= $row['hits_count']; ?>)
              </a>
            </li>  
          <?php } ?>
          </ul>
        </div>
      </div>
    <?php } ?>

  <?php } ?>
</aside>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.querySelectorAll(".focus-user")
    .forEach(el => el.addEventListener("click", function(e) {
      let content = document.querySelector('.content_<?= $topic['facet_id']; ?>');
      let div = document.querySelector(".content_<?= $topic['facet_id']; ?>");
      div.classList.remove("none");
      fetch("/topic/<?= $topic['facet_slug']; ?>/followers/<?= $topic['facet_id']; ?>", {
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