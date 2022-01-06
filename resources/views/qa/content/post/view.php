<?php $post = $data['post']; ?>
<div class="col-span-1 mb-none center">
  <div class="sticky top20">
    <?= votes($uid['user_id'], $post, 'post', 'ps', 'text-2xl middle', 'block'); ?>
    <div class="pt20">
      <?= favorite($uid['user_id'], $post['post_id'], 'post', $post['favorite_tid'], 'ps', 'text-2xl'); ?>
    </div>
  </div>
</div>
<main class="col-span-8 mb-col-12">
  <article class="post-full bg-white<?php if ($post['post_is_deleted'] == 1) { ?> bg-red-200<?php } ?> mb15 pt0 pr15 pb5 pl15">
    <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) { ?>
      <div class="post-body">
        <h1 class="mb0 mt10 font-normal text-2xl">
          <?= import('/_block/post-title', ['post' => $post, 'uid' => $uid]); ?>
        </h1>
        <div class="text-sm lowercase flex gray-400">
          <?= $post['post_date_lang']; ?>
          <?php if ($post['modified']) { ?>
            (<?= Translate::get('ed'); ?>)
          <?php } ?>

          <?php if ($uid['user_id']) { ?>
            <?php if ($uid['user_login'] == $post['user_login']  || UserData::checkAdmin()) { ?>
              <a class="gray-400 mr10 ml10" href="<?= getUrlByName('post.edit', ['id' => $post['post_id']]); ?>">
                <?= Translate::get('edit'); ?>
              </a>
            <?php } ?>
            <?php if ($uid['user_login'] == $post['user_login']) { ?>
              <?php if ($post['post_draft'] == 0) { ?>
                <?php if ($post['user_my_post'] == $post['post_id']) { ?>
                  <span class="mu_post gray-400 mr10 ml10">+ <?= Translate::get('in-the-profile'); ?></span>
                <?php } else { ?>
                  <a class="add-post-profile gray-400 mr10 ml10" data-post="<?= $post['post_id']; ?>">
                    <span class="mu_post"><?= Translate::get('in-the-profile'); ?></span>
                  </a>
                <?php } ?>
              <?php } ?>
            <?php } ?>
            <?php if (UserData::checkAdmin()) { ?>
              <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action gray-400 mr10 ml10">
                <?php if ($post['post_is_deleted'] == 1) { ?>
                  <i class="bi bi-trash red-500"></i>
                <?php } else { ?>
                  <i class="bi bi-trash"></i>
                <?php } ?>
              </a>
              <a data-id="<?= $post['post_id']; ?>" class="post-recommend gray-400 mr10 ml10">
                <?php if ($post['post_is_recommend'] == 1) { ?>
                  <i class="bi bi-lightning sky-500"></i>
                <?php } else { ?>
                  <i class="bi bi-lightning"></i>
                <?php } ?>
              </a>
            <?php } ?>
            <?= import('/_block/show-ip', ['ip' => $post['post_ip'], 'uid' => $uid]); ?>
          <?php } ?>
        </div>
      </div>

      <?php if ($post['post_thumb_img']) { ?>
        <?= post_img($post['post_thumb_img'], $post['post_title'], 'thumb right ml15 mb-ml-5', 'thumbnails'); ?>
      <?php } ?>

      <div class="post-body max-w780 full">
        <div class="post">
          <?= $post['post_content']; ?>
        </div>
        <?php if ($post['post_url_domain']) { ?>
          <div class="mb15">
            <a rel="nofollow noreferrer ugc" target="_blank" class="btn btn-primary" href="<?= $post['post_url']; ?>">
              <?= Translate::get('details are here'); ?>
              <i class="bi bi-folder-symlink middle ml5"></i>
            </a>
          </div>
        <?php } ?>
        <?php if ($post['post_url_domain']) { ?>
          <h3 class="uppercase mb5 mt0 font-light text-sm gray"><?= Translate::get('source'); ?></h3>
          <div class="italic m15 mb15 p10 text-sm bg-gray-100 table gray">
            <div>
              <i class="bi bi-link-45deg"></i>
              <a class="gray" href="<?= getUrlByName('domain', ['domain' => $post['post_url_domain']]); ?>">
                <?= $post['post_url_domain']; ?>
              </a>
            </div>
          </div>
        <?php } ?>
        <?= import('/_block/related-posts', ['related_posts' => $data['related_posts'], 'number' => 'yes', 'uid' => $uid]); ?>

        <div class="flex flex-row items-center mb20">
          <?php if (!empty($data['blog'])) { ?>
            <a title="<?= $data['blog'][0]['facet_title']; ?>" class="mr10 gray inline text-sm" href="/blog/<?= $data['blog'][0]['facet_slug']; ?>">
              <span class="bg-gray-200 pt5 pr10 pb5 pl10 br-rd5">
                <?= $data['blog'][0]['facet_title']; ?>
              </span>
            </a>
          <?php } ?>

          <?php if (!empty($data['facets'])) { ?>
            <div class="lowercase">
              <?php foreach ($data['facets'] as $topic) { ?>
                <a class="bg-blue-100 bg-hover-green white-hover pt5 pr10 pb5 pl10 br-rd20 sky-500 inline text-sm" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
                  <?= $topic['facet_title']; ?>
                </a>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
      </div>

      <div class="br-box-gray dark-br-black flex items-center mb5">
        <div class="left p10 none mb-block">
          <?= votes($uid['user_id'], $post, 'post', 'mob', 'text-2xl mr5 middle'); ?>
        </div>

        <ul class="list-none w-100 p0 m0 lowercase">
          <li class="left p10">
            <div class="text-sm gray-400 mb5">
              <?= Translate::get('created by'); ?>
            </div>
            <div class="center">
              <a title="<?= $post['user_login']; ?>" href="<?= getUrlByName('user', ['login' => $post['user_login']]); ?>">
                <?= user_avatar_img($post['user_avatar'], 'small', $post['user_login'], 'w34 br-rd-50'); ?>
              </a>
            </div>
          </li>
          <li class="left p10 mb-none">
            <div class="text-sm gray-400 mb5">
              <?= Translate::get('last answer'); ?>
            </div>
            <div class="center">
              <?php if (!empty($data['last_user']['answer_id'])) { ?>
                <a title="<?= $data['last_user']['user_login']; ?>" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>#answer_<?= $data['last_user']['answer_id']; ?>">
                  <?= user_avatar_img($data['last_user']['user_avatar'], 'small', $data['last_user']['user_login'], 'w34 br-rd-50'); ?>
                </a>
              <?php } else { ?>
                <span class="gray-400 text-sm">—</span>
              <?php } ?>
            </div>
          </li>
          <li class="left p10 mb-none">
            <div class="text-2xl gray-600 center mb5">
              <?php if ($post['post_hits_count'] == 0) { ?>
                <span class="gray-400 text-sm">—</span>
              <?php } else { ?>
                <?= $post['post_hits_count']; ?>
              <?php } ?>
            </div>
            <div class="center text-sm gray-400">
              <?= num_word($post['post_hits_count'], Translate::get('num-view'), false); ?>
            </div>
          </li>
          <li class="left p10 mb-none">
            <div class="text-2xl gray-600 center mb5">
              <?php if ($post['amount_content'] == 0) { ?>
                <span class="gray-400 text-sm">—</span>
              <?php } else { ?>
                <?= $post['amount_content']; ?>
              <?php } ?>
            </div>
            <div class="center text-sm gray-400">
              <?= num_word($post['amount_content'], Translate::get('num-answer'), false); ?>
            </div>
          </li>
        </ul>

        <div class="mr15">
          <?php if ($uid['user_id'] > 0) { ?>
            <?php if (is_array($data['post_signed'])) { ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id text-sm right mt5 bg-gray-100 gray-400 br-gray-200 br-rd20 center pt5 pr15 pb5 pl15">
                <?= Translate::get('unsubscribe'); ?>
              </div>
            <?php } else { ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id text-sm right mt5 bg-sky-50 bg-hover-gray sky-500 br-sky-100 br-rd20 center pt5 pr15 pb5 pl15">
                + <?= Translate::get('read'); ?>
              </div>
            <?php } ?>
          <?php } else { ?>
            <a class="right text-sm mt5 bg-sky-50 sky-500 bg-hover-gray br-sky-100 br-rd20 center pt5 pr15 pb5 pl15" href="<?= getUrlByName('login'); ?>">
              + <?= Translate::get('read'); ?>
            </a>
          <?php } ?>
        </div>

        <div class="right ml15 p10 none mb-block">
          <?= favorite($uid['user_id'], $post['post_id'], 'post', $post['favorite_tid'], 'mob', 'text-2xl'); ?>
        </div>
      </div>

      <?php if ($uid['user_id'] > 0) { ?>
        <?php if ($post['post_feature'] == 0 && $post['post_draft'] == 0 && $post['post_closed'] == 0) { ?>

          <form class="mb15" action="<?= getUrlByName('answer.create'); ?>" accept-charset="UTF-8" method="post">
            <?= csrf_field() ?>

            <?= import('/_block/editor/editor', [
              'uid'       => $uid,
              'height'    => '250px',
              'preview'   => 'vertical',
            ]); ?>

            <div class="clear pt5">
              <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
              <input type="hidden" name="answer_id" value="0">
              <?= sumbit(Translate::get('reply')); ?>
            </div>
          </form>

        <?php } ?>
      <?php } ?>

    <?php } else { ?>
      <div class="bg-red-300 p15 center mr10">
        <?= Translate::get('post deleted'); ?>...
      </div>
    <?php } ?>
  </article>
  <div id="comment"></div>
  <?php if ($post['post_draft'] == 0) {
    if ($post['post_feature'] == 0) {
      import('/_block/comments-view', ['data' => $data, 'post' => $post, 'uid' => $uid]);
      if ($post['post_closed'] == 1) echo no_content(Translate::get('the post is closed'), 'bi bi-door-closed');
    } else {
      import('/_block/questions-view', ['data' => $data, 'post' => $post, 'uid' => $uid]);
      if ($post['post_closed'] == 1) echo no_content(Translate::get('the question is closed'), 'bi bi-door-closed');
    }
  } else {
    echo no_content(Translate::get('this is a draft'), 'bi bi-journal-medical');
  } ?>
</main>
<aside class="col-span-3 relative br-rd5 mb-none">

  <?php if (!empty($data['topics'])) { ?>
    <div class="br-box-gray box-shadow-all bg-white br-rd5 mb15 p15">
      <h3 class="uppercase mb5 mt0 font-light gray"><?= Translate::get('topics'); ?></h3>
      <?php foreach ($data['topics'] as $topic) { ?>
        <?php if ($uid['user_id']) { ?>
          <?php if (!$topic['signed_facet_id']) { ?>
            <div data-id="<?= $topic['facet_id']; ?>" data-type="topic" class="focus-id right inline text-sm sky-500 center mt5 mr5">
              <i class="bi bi-plus"></i> <?= Translate::get('read'); ?>
            </div>
          <?php } ?>
        <?php } ?>
        <a class="flex justify-center pt5 pr10 pb5 black dark-gray-300 inline text-sm" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
          <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w24 mr10 br-box-gray'); ?>
          <?= $topic['facet_title']; ?>
          <div class="gray-400 text-sm"><?= $topic['facet_short_description']; ?></div>
        </a>
      <?php } ?>
    </div>
  <?php } ?>

  <?php if ($post['post_content_img']) { ?>
    <div class="br-box-gray box-shadow-all bg-white br-rd5 mb15 post-img">
      <?= post_img($post['post_content_img'], $post['post_title'], 'w-100 p15 br-rd5', 'cover', $post['post_content_img']); ?>
    </div>
  <?php } ?>
  <div class="share-btn br-box-gray box-shadow-all bg-white br-rd5 mb15 p15 text-2xl center">
    <a class="p15 gray-400" data-id="fb"><i class="bi bi-facebook"></i></a>
    <a class="p15 gray-400" data-id="vk">VK</a>
    <a class="p15 gray-400" data-id="tw"><i class="bi bi-twitter"></i></a>
  </div>
  <?php if ($data['recommend']) { ?>
    <div class="br-box-gray bg-white box-shadow-all br-rd5 mb15 sticky top20 p15">
      <h3 class="uppercase mb10 mt0 font-light text-sm gray dark-gray-300"><?= Translate::get('recommended'); ?></h3>
      <?php foreach ($data['recommend'] as  $rec_post) { ?>
        <div class="mb15 hidden flex text-sm"> 
          <?php if ($rec_post['post_type'] == 'post') { ?>
            <a class="gray" href="<?= getUrlByName('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
              <?php if ($rec_post['post_answers_count'] > 0) { ?>
                <div class="p5 pr10 pb5 pl10 bg-green-600 br-rd3 white center mr15">
                  <?= $rec_post['post_answers_count'] ?>
                </div>
              <?php } else { ?>
                <div class="p5 pr10 pb5 pl10 bg-gray-300 br-rd3 gray center mr15">0</div>
              <?php } ?>
            </a>
          <?php } else { ?>
            <i class="bi bi-intersect gray-400 middle mr15 text-2xl"></i>
          <?php } ?>          
          <a class="black dark-gray-300" href="<?= getUrlByName('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
            <?= $rec_post['post_title']; ?>
          </a>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
</aside>
<script nonce="<?= $_SERVER['nonce']; ?>">
  // Modal windows for photos in a post
  document.querySelectorAll(".post-body.full .post img, .post-img img, .answ-telo p img")
    .forEach(el => el.addEventListener("click", function(e) {
      if (el.src) {
        let img = '<img src="' + el.src + '">';
        Swal.fire({
          width: '100%',
          showConfirmButton: false,
          showCloseButton: true,
          title: img
        })
      }
    }));

  <?php if ($uid['user_id'] > 0) { ?>
    document.querySelectorAll(".msg-flag")
      .forEach(el => el.addEventListener("click", function(e) {
        let post_id = el.dataset.post_id;
        let content_id = el.dataset.content_id;
        let type = el.dataset.type;
        Swal.fire({
          title: '<?= Translate::get('report'); ?>',
          html: '<?= Translate::get('does this violate site rules'); ?>?',
          confirmButtonColor: '#3085d6',
          confirmButtonText: '<?= Translate::get('yes'); ?>',
          cancelButtonText: '<?= Translate::get('no'); ?>',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          preConfirm: () => {
            fetch("/flag/repost", {
                method: "POST",
                body: "type=" + type + "&post_id=" + post_id + "&content_id=" + content_id,
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                }
              })
              .then(
                response => {
                  return;
                }
              ).then(
                text => {}
              );
          }
        })
      }));
  <?php } ?>
</script>
</div>
<?= import('/_block/wide-footer', ['uid' => $uid]); ?>