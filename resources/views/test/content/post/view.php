<?php $post = $data['post']; ?>
<main class="w-100">
  <article class="post-full box<?php if ($post['post_is_deleted'] == 1) : ?> bg-red-200<?php endif; ?>">
    <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
      <div class="post-body">

        <div class="flex flex-row items-center">
          <?php if (!empty($data['blog'])) : ?>
            <a title="<?= $data['blog'][0]['facet_title']; ?>" class="mr10 text-sm" href="/blog/<?= $data['blog'][0]['facet_slug']; ?>">
              <?= $data['blog'][0]['facet_title']; ?>
            </a>
          <?php endif; ?>

          <?php if (!empty($data['facets'])) : ?>
            <div class="lowercase">
              <?php foreach ($data['facets'] as $topic) : ?>
                <a class="tags" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>">
                  <?= $topic['facet_title']; ?>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <h1><?= $post['post_title']; ?>
          <?= insert('/content/post/post-title', ['post' => $post]); ?>
        </h1>
        <div class="text-sm lowercase flex gray-600">
          <?= Html::langDate($post['post_date']); ?>
          <?php if ($post['modified']) : ?>
            (<?= __('app.ed'); ?>)
          <?php endif; ?>

          <?php if (UserData::checkActiveUser()) : ?>
            <?php if (UserData::getUserLogin() == $post['login']  || UserData::checkAdmin()) : ?>
              <a class="gray-600 mr10 ml10" href="<?= url('content.edit', ['type' => 'post', 'id' => $post['post_id']]); ?>">
                <?= __('app.edit'); ?>
              </a>
            <?php endif; ?>
            <?php if (UserData::getUserLogin() == $post['login']) : ?>
              <?php if ($post['my_post'] == $post['post_id']) : ?>
                <span class="add-profile active mr10 ml10" data-post="<?= $post['post_id']; ?>">
                  + <?= __('app.in_profile'); ?>
                </span>
              <?php else : ?>
                <span class="add-profile mr10 ml10" data-post="<?= $post['post_id']; ?>">
                  <?= __('app.in_profile'); ?>
                </span>
              <?php endif; ?>
            <?php endif; ?>
            <?php if (UserData::checkAdmin()) : ?>
              <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action gray-600 mr10 ml10">
                <?php if ($post['post_is_deleted'] == 1) : ?>
                  <i class="bi-trash red"></i>
                <?php else : ?>
                  <i class="bi-trash"></i>
                <?php endif; ?>
              </a>
              <a data-id="<?= $post['post_id']; ?>" class="post-recommend gray-600 mr10 ml10">
                <?php if ($post['post_is_recommend'] == 1) : ?>
                  <i class="bi-lightning sky"></i>
                <?php else : ?>
                  <i class="bi-lightning"></i>
                <?php endif; ?>
              </a>
            <?php endif; ?>
            <?= insert('/_block/show-ip', ['ip' => $post['post_ip'], 'publ' => $post['post_published']]); ?>
          <?php endif; ?>
        </div>
      </div>

      <?php if ($post['post_thumb_img']) : ?>
        <?= Html::image($post['post_thumb_img'], $post['post_title'],  'thumb right ml15', 'post', 'thumbnails'); ?>
      <?php endif; ?>

      <div class="post-body max-w780 full">
        <div class="post">
          <?= Content::text($post['post_content'], 'text'); ?>
        </div>
        <?php if ($post['post_url_domain']) : ?>
          <div class="mb15">
            <a rel="nofollow noreferrer ugc" target="_blank" class="btn btn-primary" href="<?= $post['post_url']; ?>">
              <?= __('app.details_here'); ?>
              <i class="bi-folder-symlink middle ml5"></i>
            </a>
          </div>
        <?php endif; ?>
        <?php if ($post['post_url_domain']) : ?>
          <h3 class="uppercase-box"><?= __('app.source'); ?></h3>
          <div class="italic m15 mb15 p10 text-sm bg-lightgray table gray">
            <div>
              <i class="bi-link-45deg"></i>
              <a class="gray" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
                <?= $post['post_url_domain']; ?>
              </a>
            </div>
          </div>
        <?php endif; ?>
        <?= insert('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>
      </div>

      <div class="br-gray flex items-center mb5">
        <div class="left p10 block">
          <?= Html::votes($post, 'post', 'ps', 'bi-arrow-up-short text-2xl mr5 middle'); ?>
        </div>

        <ul class="list-none w-100 lowercase">
          <li class="left p10">
            <div class="text-sm gray-600 mb5">
              <?= __('app.created_by'); ?>
            </div>
            <div class="center">
              <a title="<?= $post['login']; ?>" href="<?= url('profile', ['login' => $post['login']]); ?>">
                <?= Html::image($post['avatar'], $post['login'], 'img-base', 'avatar', 'small'); ?>
              </a>
            </div>
          </li>
          <li class="left p10 mb-none">
            <div class="text-sm gray-600 mb5">
              <?= __('app.last_answer'); ?>
            </div>
            <div class="center">
              <?php if (!empty($data['last_user']['answer_id'])) : ?>
                <a title="<?= $data['last_user']['login']; ?>" href="<?= url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>#answer_<?= $data['last_user']['answer_id']; ?>">
                  <?= Html::image($data['last_user']['avatar'], $data['last_user']['login'], 'img-base', 'avatar', 'small'); ?>
                </a>
              <?php else : ?>
                <span class="gray-600 text-sm">—</span>
              <?php endif; ?>
            </div>
          </li>
          <li class="left p10 mb-none gray-600 text-2xl">
            <div class="text-2xl center mb5">
              <?php if ($post['post_hits_count'] == 0) : ?>
                —
              <?php else : ?>
                <?= $post['post_hits_count']; ?>
              <?php endif; ?>
            </div>
            <div class="center text-sm">
              <?= Html::numWord($post['post_hits_count'], __('app.num_view'), false); ?>
            </div>
          </li>
          <li class="left p10 mb-none gray-600 text-sm">
            <div class="text-2xl center mb5">
              <?php if ($post['amount_content'] == 0) : ?>
                —
              <?php else : ?>
                <?= $post['amount_content']; ?>
              <?php endif; ?>
            </div>
            <div class="center">
              <?= Html::numWord($post['amount_content'], __('app.num_answer'), false); ?>
            </div>
          </li>
        </ul>

        <div class="mr15">
          <?php if (UserData::checkActiveUser()) : ?>
            <?php if (is_array($data['post_signed'])) : ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id right mt5 yes">
                <?= __('app.unsubscribe'); ?>
              </div>
            <?php else : ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id right mt5 no">
                + <?= __('app.read'); ?>
              </div>
            <?php endif; ?>
          <?php else : ?>
            <a class="right mt5 focus-id no" href="<?= url('login'); ?>">
              + <?= __('app.read'); ?>
            </a>
          <?php endif; ?>
        </div>

        <div class="right ml15 p10 none mb-block">
          <?= Html::favorite($post['post_id'], 'post', $post['tid'], 'mob', 'text-2xl'); ?>
        </div>
      </div>

      <?php if (UserData::checkActiveUser()) : ?>
        <?php if ($post['post_feature'] == 0 && $post['post_draft'] == 0 && $post['post_closed'] == 0) : ?>

          <form action="<?= url('content.create', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
            <?= csrf_field() ?>

            <?= insert('/_block/form/editor', ['height'  => '250px', 'type' => 'answer', 'id' => $post['post_id']]); ?>

            <div class="clear pt5">
              <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
              <input type="hidden" name="answer_id" value="0">
              <?= Html::sumbit(__('app.reply')); ?>
            </div>
          </form>

        <?php endif; ?>
      <?php endif; ?>

    <?php else : ?>
      <div class="bg-red-200 p15 center mr10">
        <?= __('app.content_deleted', ['name' => __('app.post')]); ?>...
      </div>
    <?php endif; ?>
  </article>
  <div id="comment"></div>
  <?php if ($post['post_draft'] == 0) :
    if ($post['post_feature'] == 0) :
      insert('/_block/comments-view', ['data' => $data, 'post' => $post]);
      if ($post['post_closed'] == 1) :
        echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.post_closed'), 'icon' => 'bi-door-closed']);
      endif;
    else :
      insert('/_block/questions-view', ['data' => $data, 'post' => $post]);
      if ($post['post_closed'] == 1) :
        echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.question_closed'), 'icon' => 'bi-door-closed']);
      endif;
    endif;
  else :
    echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.this_draft'), 'icon' => 'bi-door-closed']);
  endif; ?>
</main>
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
        let comment = document.getElementById('qcomment');
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
<?= insert('/_block/js-msg-flag'); ?>