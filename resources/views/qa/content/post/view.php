<?php $post = $data['post']; ?>
<div class="w110 mb-none center">
  <div class="sticky top-xl">
    <?= Html::votes($user['id'], $post, 'post', 'ps', 'text-2xl middle mt15', 'block'); ?>
    <div class="pt20">
      <?= Html::favorite($user['id'], $post['post_id'], 'post', $post['tid'], 'ps', 'text-2xl'); ?>
    </div>
  </div>
</div>
<main>
  <article class="post-full <?php if ($post['post_is_deleted'] == 1) { ?> bg-red-200<?php } ?>">
    <?php if ($post['post_is_deleted'] == 0 || $user['trust_level'] == UserData::REGISTERED_ADMIN) { ?>
      <div class="post-body">

        <div class="flex flex-row items-center">
          <?php if (!empty($data['blog'])) { ?>
            <a title="<?= $data['blog'][0]['facet_title']; ?>" class="mr10 gray-600 text-sm" href="/blog/<?= $data['blog'][0]['facet_slug']; ?>">
              <?= $data['blog'][0]['facet_title']; ?>
            </a>
          <?php } ?>

          <?php if (!empty($data['facets'])) { ?>
            <div class="lowercase">
              <?php foreach ($data['facets'] as $topic) { ?>
                <a class="tags" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
                  <?= $topic['facet_title']; ?>
                </a>
              <?php } ?>
            </div>
          <?php } ?>
        </div>

        <h1><?= $post['post_title']; ?>
          <?= Tpl::import('/content/post/post-title', ['post' => $post]); ?>
        </h1>
        <div class="text-sm lowercase flex gray-600">
          <?= Html::langDate($post['post_date']); ?>
          <?php if ($post['modified']) { ?>
            (<?= Translate::get('ed'); ?>)
          <?php } ?>

          <?php if ($user['id']) { ?>
            <?php if ($user['login'] == $post['login']  || UserData::checkAdmin()) { ?>
              <a class="gray-600 mr10 ml10" href="<?= getUrlByName('content.edit', ['type' => 'post', 'id' => $post['post_id']]); ?>">
                <?= Translate::get('edit'); ?>
              </a>
            <?php } ?>
            <?php if ($user['login'] == $post['login']) { ?>
              <?php if ($post['my_post'] == $post['post_id']) { ?>
                <span class="add-profile active mr10 ml10" data-post="<?= $post['post_id']; ?>">
                  + <?= Translate::get('in.profile'); ?>
                </span>
              <?php } else { ?>
                <span class="add-profile mr10 ml10" data-post="<?= $post['post_id']; ?>">
                  <?= Translate::get('in.profile'); ?>
                </span>
              <?php } ?>
            <?php } ?>
            <?php if (UserData::checkAdmin()) { ?>
              <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action gray-600 mr10 ml10">
                <?php if ($post['post_is_deleted'] == 1) { ?>
                  <i class="bi-trash red"></i>
                <?php } else { ?>
                  <i class="bi-trash"></i>
                <?php } ?>
              </a>
              <a data-id="<?= $post['post_id']; ?>" class="post-recommend gray-600 mr10 ml10">
                <?php if ($post['post_is_recommend'] == 1) { ?>
                  <i class="bi-lightning sky"></i>
                <?php } else { ?>
                  <i class="bi-lightning"></i>
                <?php } ?>
              </a>
            <?php } ?>
            <?= Tpl::import('/_block/show-ip', ['ip' => $post['post_ip'], 'user' => $user, 'publ' => $post['post_published']]); ?>
          <?php } ?>
        </div>
      </div>

      <?php if ($post['post_thumb_img']) { ?>
        <?= Html::image($post['post_thumb_img'], $post['post_title'],  'thumb right ml15', 'post', 'thumbnails'); ?>
      <?php } ?>

      <div class="post-body max-w780 full">
        <div class="post">
          <?= Content::text($post['post_content'], 'text'); ?>
        </div>
        <?php if ($post['post_url_domain']) { ?>
          <div class="mb15">
            <a rel="nofollow noreferrer ugc" target="_blank" class="btn btn-primary" href="<?= $post['post_url']; ?>">
              <?= Translate::get('details.here'); ?>
              <i class="bi-folder-symlink middle ml5"></i>
            </a>
          </div>
        <?php } ?>
        <?php if ($post['post_url_domain']) { ?>
          <h3 class="uppercase-box"><?= Translate::get('source'); ?></h3>
          <div class="italic m15 mb15 p10 text-sm bg-gray-100 table gray">
            <div>
              <i class="bi-link-45deg"></i>
              <a class="gray" href="<?= getUrlByName('domain', ['domain' => $post['post_url_domain']]); ?>">
                <?= $post['post_url_domain']; ?>
              </a>
            </div>
          </div>
        <?php } ?>
        <?= Tpl::import('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>
      </div>

      <div class="br-box-gray flex items-center mb5">
        <div class="left p10 none mb-block">
          <?= Html::votes($user['id'], $post, 'post', 'mob', 'text-2xl mr5 middle'); ?>
        </div>

        <ul class="list-none w-100 lowercase">
          <li class="left p10">
            <div class="text-sm gray-600 mb5">
              <?= Translate::get('created.by'); ?>
            </div>
            <div class="center">
              <a title="<?= $post['login']; ?>" href="<?= getUrlByName('profile', ['login' => $post['login']]); ?>">
                <?= Html::image($post['avatar'], $post['login'], 'ava-base', 'avatar', 'small'); ?>
              </a>
            </div>
          </li>
          <li class="left p10 mb-none">
            <div class="text-sm gray-600 mb5">
              <?= Translate::get('last.answer'); ?>
            </div>
            <div class="center">
              <?php if (!empty($data['last_user']['answer_id'])) { ?>
                <a title="<?= $data['last_user']['login']; ?>" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>#answer_<?= $data['last_user']['answer_id']; ?>">
                  <?= Html::image($data['last_user']['avatar'], $data['last_user']['login'], 'ava-base', 'avatar', 'small'); ?>
                </a>
              <?php } else { ?>
                <span class="gray-600 text-sm">—</span>
              <?php } ?>
            </div>
          </li>
          <li class="left p10 mb-none gray-600 text-2xl">
            <div class="text-2xl center mb5">
              <?php if ($post['post_hits_count'] == 0) { ?>
                —
              <?php } else { ?>
                <?= $post['post_hits_count']; ?>
              <?php } ?>
            </div>
            <div class="center text-sm">
              <?= Html::numWord($post['post_hits_count'], Translate::get('num-view'), false); ?>
            </div>
          </li>
          <li class="left p10 mb-none gray-600 text-sm">
            <div class="text-2xl center mb5">
              <?php if ($post['amount_content'] == 0) { ?>
                —
              <?php } else { ?>
                <?= $post['amount_content']; ?>
              <?php } ?>
            </div>
            <div class="center">
              <?= Html::numWord($post['amount_content'], Translate::get('num-answer'), false); ?>
            </div>
          </li>
        </ul>

        <div class="mr15">
          <?php if ($user['id'] > 0) { ?>
            <?php if (is_array($data['post_signed'])) { ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id right mt5 yes">
                <?= Translate::get('unsubscribe'); ?>
              </div>
            <?php } else { ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id right mt5 no">
                + <?= Translate::get('read'); ?>
              </div>
            <?php } ?>
          <?php } else { ?>
            <a class="right mt5 focus-id no" href="<?= getUrlByName('login'); ?>">
              + <?= Translate::get('read'); ?>
            </a>
          <?php } ?>
        </div>

        <div class="right ml15 p10 none mb-block">
          <?= Html::favorite($user['id'], $post['post_id'], 'post', $post['tid'], 'mob', 'text-2xl'); ?>
        </div>
      </div>

      <?php if ($user['id'] > 0) { ?>
        <?php if ($post['post_feature'] == 0 && $post['post_draft'] == 0 && $post['post_closed'] == 0) { ?>

          <form action="<?= getUrlByName('content.create', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
            <?= csrf_field() ?>

            <?= Tpl::import('/_block/editor/editor', ['height'  => '250px', 'type' => 'answer', 'id' => $post['post_id']]); ?>

            <div class="clear pt5">
              <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
              <input type="hidden" name="answer_id" value="0">
              <?= Html::sumbit(Translate::get('reply')); ?>
            </div>
          </form>

        <?php } ?>
      <?php } ?>

    <?php } else { ?>
      <div class="bg-red-200 p15 center mr10">
        <?= sprintf(Translate::get('content.deleted'), Translate::get('post')); ?>...
      </div>
    <?php } ?>
  </article>
  <div id="comment"></div>
  <?php if ($post['post_draft'] == 0) {
    if ($post['post_feature'] == 0) {
      Tpl::import('/_block/comments-view', ['data' => $data, 'post' => $post, 'user' => $user]);
      if ($post['post_closed'] == 1) {
        echo Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('post.closed'), 'icon' => 'bi-door-closed']);
      }  
    } else {
      Tpl::import('/_block/questions-view', ['data' => $data, 'post' => $post, 'user' => $user]);
      if ($post['post_closed'] == 1) {
        echo Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('question.closed'), 'icon' => 'bi-door-closed']);
      }    
    }
  } else {
    echo Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('this.draft'), 'icon' => 'bi-info-lg']); 
  } ?>
</main>
<aside>
  <?php if (!empty($data['facets'])) { ?>
    <div class="box bg-violet-50">
      <h3 class="uppercase-box"><?= Translate::get('topics'); ?></h3>
      <?php foreach ($data['facets'] as $topic) { ?>
        <?= Html::image($topic['facet_img'], $topic['facet_title'], 'img-base', 'logo', 'max'); ?>

        <?php if (!$topic['signed_facet_id'] && $user['id']) { ?>
          <div data-id="<?= $topic['facet_id']; ?>" data-type="facet" class="focus-id right inline text-sm sky center mt5 mr5">
              <i class="bi-plus"></i> <?= Translate::get('read'); ?>
          </div>
        <?php } ?>

        <a title="<?= $topic['facet_title']; ?>" class="black inline text-sm" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
            <?= $topic['facet_title']; ?>
        </a>

        <div class="text-sm mt5 pr15 mb-pr0 gray-600">
          <?= $topic['facet_short_description']; ?>
        </div>
      <?php } ?>
    </div>
  <?php } ?>

  <?php if ($post['post_content_img']) { ?>
    <div class="box bg-violet-50">
      <img class="preview w-100 br-rd5" src="<?= PATH_POSTS_COVER . $post['post_content_img']; ?>" alt="<?= $post['post_title']; ?>">
    </div>
  <?php } ?>
  <div class="center box bg-violet-50">
     <?= Tpl::import('/share'); ?>
  </div>
  <?php if ($data['recommend']) { ?>
    <div class="box bg-violet-50 sticky top-sm">
      <h3 class="uppercase-box"><?= Translate::get('recommended'); ?></h3>
      <?php foreach ($data['recommend'] as  $rec_post) { ?>
        <div class="mb15 hidden flex text-sm">
          <?php if ($rec_post['post_type'] == 'post') { ?>
            <a class="gray" href="<?= getUrlByName('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
              <?php if ($rec_post['post_answers_count'] > 0) { ?>
                <div class="p5 pr10 pb5 pl10 bg-green br-rd3 white center mr15">
                  <?= $rec_post['post_answers_count'] ?>
                </div>
              <?php } else { ?>
                <div class="p5 pr10 pb5 pl10 bg-gray-100 br-rd3 gray center mr15">0</div>
              <?php } ?>
            </a>
          <?php } else { ?>
            <i class="bi-intersect gray-600 middle mr15 text-2xl"></i>
          <?php } ?>
          <a class="black" href="<?= getUrlByName('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
            <?= $rec_post['post_title']; ?>
          </a>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
</aside>
<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    mediumZoom(document.querySelectorAll('.post-body.full .post img:not(.emoji), img.preview, .content-body p img:not(.emoji)'))
    // Добавим цитирование    
    function get_text() {
      var text;
      if (window.getSelection) {
        text = window.getSelection().toString();
      } else if (document.getSelection) {
        // Старый способ
        text = document.getSelection();
      } else if (document.selection) {
        // IE, или уберем?
        text = document.selection.createRange().text;
      }

      if (text) {
        let comment =  document.getElementById('qcomment');
        if (comment) {
            comment.innerHTML = '> ' + text;
        }
      }
    }
    // Применять эту функцию к тегам, содержащим text
    var p_arr = document.getElementsByTagName("p");
    for (var i = 0; i < p_arr.length; i++) {
      p_arr[i].onmouseup = get_text;
    }
  });
</script>
<?= Tpl::import('/_block/js-msg-flag', ['uid' => $user['id']]); ?>