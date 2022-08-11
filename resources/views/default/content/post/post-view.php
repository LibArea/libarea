<?php $post = $data['post']; ?>
<main>
  <article class="post-full mb15<?php if ($post['post_is_deleted'] == 1) : ?> bg-red-200<?php endif; ?>">
    <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
      <div class="post-body">

        <?php if (!empty($data['united'])) : ?>
          <div class="box bg-lightyellow mb15 gray-600">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#git-merge"></use>
            </svg>
            <?= __('app.post_merged'); ?>
            <?php foreach ($data['united'] as $merged) : ?>
              (id <?= $merged['post_id']; ?>):
              <?php if (UserData::checkAdmin()) : ?>
                <a href="/post/<?= $merged['post_id']; ?>"><?= $merged['post_title']; ?></a>
              <?php else : ?>
                <?= $merged['post_title']; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="flex flex-row items-center">
          <?php if (!empty($data['blog'])) : ?>
            <a title="<?= $data['blog'][0]['facet_title']; ?>" class="tag-violet" href="/blog/<?= $data['blog'][0]['facet_slug']; ?>">
              <?= $data['blog'][0]['facet_title']; ?>
            </a>
          <?php endif; ?>

          <?php if (!empty($data['facets'])) : ?>
            <?php foreach ($data['facets'] as $topic) : ?>
              <a class="tag" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>"><?= $topic['facet_title']; ?></a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <h1 class="m0"><?= $post['post_title']; ?>
          <?= insert('/content/post/post-title', ['post' => $post]); ?>
        </h1>
        <div class="text-sm flex gray-600 gap">
          <?= Html::langDate($post['post_date']); ?>
          <?php if ($post['modified']) : ?>
            (<?= __('app.ed'); ?>)
          <?php endif; ?>

          <?php if (UserData::checkActiveUser()) : ?>

            <?php if (UserData::getUserLogin() == $post['login']  || UserData::checkAdmin()) : ?>
              <a class="gray-600 lowercase" href="<?= url('content.edit', ['type' => 'post', 'id' => $post['post_id']]); ?>">
                <?= __('app.edit'); ?>
              </a>
            <?php endif; ?>
            <?php if (UserData::getUserLogin() == $post['login']) : ?>
              <?php if ($post['my_post'] == $post['post_id']) : ?>
                <span class="add-profile" data-post="<?= $post['post_id']; ?>">
                  - <?= __('app.in_profile'); ?>
                </span>
              <?php else : ?>
                <span class="add-profile active" data-post="<?= $post['post_id']; ?>">
                  + <?= __('app.in_profile'); ?>
                </span>
              <?php endif; ?>
            <?php endif; ?>

            <?= insert('/_block/admin-dropdown-post', ['post' => $post]); ?>
          <?php endif; ?>

        </div>
      </div>

      <?php if ($post['post_thumb_img']) : ?>
        <?= Img::image($post['post_thumb_img'], $post['post_title'],  'thumb max-w-100', 'post', 'thumbnails'); ?>
      <?php endif; ?>

      <div class="post-body max-w780 full">
        <div class="post mb15">
          <?= Content::text($post['post_content'], 'text'); ?>
        </div>
        <?php if ($post['post_url_domain']) : ?>
          <div class="mb15">
            <a rel="nofollow noreferrer ugc" target="_blank" class="btn btn-outline-primary" href="<?= $post['post_url']; ?>">
              <?= __('app.details_here'); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ($post['post_url_domain']) : ?>
          <h4 class="uppercase-box"><?= __('app.source'); ?></h4>
          <div class="italic m15 mb15 p10 text-sm bg-lightgray table gray">
            <div>
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#link"></use>
              </svg>
              <a class="gray" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
                <?= $post['post_url_domain']; ?>
              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <div class="p15 br-gray items-center flex justify-between">
        <div class="items-center flex gap gray-600">
          <?= Html::votes($post, 'post'); ?>
          <div class="items-center flex gap-min">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#eye"></use>
            </svg>
            <?= $post['post_hits_count'] == 0 ? 1 : $post['post_hits_count']; ?>
          </div>
        </div>
        <div class="items-center flex gap-max">
          <?php if (UserData::checkActiveUser()) : ?>
            <?php if (is_array($data['post_signed'])) : ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id right mt5 gray-600">
                <?= __('app.unsubscribe'); ?>
              </div>
            <?php else : ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id right mt5 red">
                + <?= __('app.read'); ?>
              </div>
            <?php endif; ?>
          <?php else : ?>
            <a class="right mt5 focus-id red" href="<?= url('login'); ?>">
              + <?= __('app.read'); ?>
            </a>
          <?php endif; ?>
          <?= Html::favorite($post['post_id'], 'post', $post['tid'], 'text-2xl ml5'); ?>
        </div>
      </div>
      <div class="box bg-lightgray">
        <a class="black" title="<?= $post['login']; ?>" href="<?= url('profile', ['login' => $post['login']]); ?>">
          <?= Img::avatar($post['avatar'], $post['login'], 'img-base mr5', 'small'); ?>
          <?= $post['login']; ?>
        </a>
        <?php if ($post['up_count'] > 0) : ?>
          <sup class="text-sm gray-600 inline"><span class="red">+</span> <?= Html::formatToHuman($post['up_count']); ?></sup>
        <?php endif; ?>
      </div>

      <?= insert('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>

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
      <div class="box center bg-red-200">
        <?= __('app.content_deleted', ['name' => __('app.post')]); ?>...
      </div>
    <?php endif; ?>
  </article>
  <div id="comment"></div>
  <?php if ($post['post_draft'] == 0) :
    if ($post['post_feature'] == 0) :
      insert('/_block/comments-view', ['data' => $data, 'post' => $post]);
    else :
      insert('/_block/questions-view', ['data' => $data, 'post' => $post]);
    endif;
  else :
    echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.this_draft'), 'icon' => 'closed']);
  endif; ?>
</main>
<aside>
  <?php if (!empty($data['facets'])) : ?>
    <div class="box bg-lightgray">
      <h4 class="uppercase-box"><?= __('app.topics'); ?></h4>
      <?php foreach ($data['facets'] as $topic) : ?>
        <?= Img::image($topic['facet_img'], $topic['facet_title'], 'img-base mr5', 'logo', 'max'); ?>

        <?php if (!$topic['signed_facet_id'] && UserData::getUserId()) : ?>
          <div data-id="<?= $topic['facet_id']; ?>" data-type="facet" class="focus-id right inline text-sm red center mt5 mr5">
            <?= __('app.read'); ?>
          </div>
        <?php endif; ?>

        <a title="<?= $topic['facet_title']; ?>" class="black text-sm" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>">
          <?= $topic['facet_title']; ?>
        </a>

        <div class="text-sm gray-600">
          <?= $topic['facet_short_description']; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($post['post_content_img']) : ?>
    <div class="box bg-lightgray img-preview">
      <img class="w-100 br-rd5" src="<?= Img::PATH['posts_cover'] . $post['post_content_img']; ?>" alt="<?= $post['post_title']; ?>">
    </div>
  <?php endif; ?>
  <div class="center box bg-lightgray">
    <?= insert('/share'); ?>
  </div>
  <?php if ($data['recommend']) : ?>
    <div class="box sticky top-sm">
      <h4 class="uppercase-box"><?= __('app.recommended'); ?></h4>
      <?php foreach ($data['recommend'] as  $rec_post) : ?>
        <div class="mb15 hidden flex text-sm">
          <?php if ($rec_post['post_type'] == 'post') : ?>
            <a class="gray" href="<?= url('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
              <?php if ($rec_post['post_answers_count'] > 0) : ?>
                <div class="p5-10 bg-green br-rd3 white center mr15">
                  <?= $rec_post['post_answers_count'] ?>
                </div>
              <?php else : ?>
                <div class="p5-10 bg-lightgray br-rd3 gray center mr15">0</div>
              <?php endif; ?>
            </a>
          <?php else : ?>
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#closed"></use>
            </svg>
          <?php endif; ?>
          <a class="black" href="<?= url('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
            <?= $rec_post['post_title']; ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <!-- мой блог -->
  <?php endif; ?>
</aside>
<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
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