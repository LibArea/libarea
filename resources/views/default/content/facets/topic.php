<?php $topic = $data['facet']; ?>
<main>
  <?php if ($topic['facet_is_deleted'] == 0) { ?>
    <?= Tpl::insert('/content/facets/topic-header', ['topic' => $topic, 'user' => $user, 'data' => $data]); ?>
    <?= Tpl::insert('/content/post/post', ['data' => $data, 'user' => $user]); ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('topic', ['slug' => $topic['facet_slug']])); ?>

  <?php } else { ?>
    <div class="center">
      <i class="bi-x-octagon text-8xl"></i>
      <div class="mt5 gray"><?= __('remote'); ?></div>
    </div>
  <?php } ?>
</main>
<aside>
  <?php if ($topic['facet_is_deleted'] == 0) { ?>
    <div class="box-flex justify-between">
      <div class="center">
        <div class="uppercase text-sm gray-600"><?= __('posts'); ?></div>
        <?= $topic['facet_count']; ?>
      </div>
      <div class="center relative">
        <div class="uppercase text-sm gray-600"><?= __('reads'); ?></div>
        <div class="focus-user sky">
          <?= $topic['facet_focus_count']; ?>
        </div>
        <div class="content_<?= $topic['facet_id']; ?> absolute bg-white box-shadow-all z-10 right0"></div>
      </div>
    </div>

    <?php if (!empty($data['pages'])) { ?>
      <div class="sticky top0 top-sm">
        <div class="box text-sm">
          <h3 class="uppercase-box"><?= __('pages'); ?></h3>
          <?php foreach ($data['pages'] as $ind => $row) { ?>
            <a class="flex pb10 items-center gray-600" href="">
              <?= $row['post_title']; ?>
            </a>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

    <?= Tpl::insert('/_block/sidebar/topic', ['data' => $data]); ?>
    <?php if (!empty($data['writers'])) { ?>
      <div class="sticky top0 top-sm">
        <div class="box text-sm">
          <h3 class="uppercase-box"><?= __('writers'); ?></h3>
          <ul>
            <?php foreach ($data['writers'] as $ind => $row) { ?>
              <li class="mb10">
                <a class="gray-600" href="<?= getUrlByName('profile', ['login' => $row['login']]); ?>">
                  <?= Html::image($row['avatar'], $row['login'], 'ava-sm', 'avatar', 'max'); ?>
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