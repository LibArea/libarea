<?php 
insert('/_block/add-js-css');
$post = $data['post']; 
?>

<main class="wrap">
  <article class="indent-body<?php if ($post['post_is_deleted'] == 1) : ?> bg-red-200<?php endif; ?>">
    <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
        <div class="flex flex-row gap items-center">
          <?php if (!empty($data['blog'])) : ?>
            <a title="<?= $data['blog'][0]['facet_title']; ?>" class="text-sm" href="/blog/<?= $data['blog'][0]['facet_slug']; ?>">
              <?= $data['blog'][0]['facet_title']; ?>
            </a>
          <?php endif; ?>

          <?php if (!empty($data['facets'])) : ?>
            <div class="lowercase">
              <?php foreach ($data['facets'] as $topic) : ?>
                <a class="tag" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>">
                  <?= $topic['facet_title']; ?>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <h1 class="m0"><?= $post['post_title']; ?>
          <?= insert('/content/post/post-title', ['post' => $post]); ?>
        </h1>
        <div class="text-sm lowercase flex gap gray-600">
          <?= Html::langDate($post['post_date']); ?>
          <?php if ($post['modified']) : ?>
            (<?= __('app.ed'); ?>)
          <?php endif; ?>

          <?php if (UserData::checkActiveUser()) : ?>
            <?php if (UserData::getUserLogin() == $post['login']  || UserData::checkAdmin()) : ?>
              <a class="gray-600" href="<?= url('content.edit', ['type' => 'post', 'id' => $post['post_id']]); ?>">
                <?= __('app.edit'); ?>
              </a>
            <?php endif; ?>
            <?php if (UserData::getUserLogin() == $post['login']) : ?>
              <?php if ($post['my_post'] == $post['post_id']) : ?>
                <span class="add-profile active" data-post="<?= $post['post_id']; ?>">
                  + <?= __('app.in_profile'); ?>
                </span>
              <?php else : ?>
                <span class="add-profile" data-post="<?= $post['post_id']; ?>">
                  <?= __('app.in_profile'); ?>
                </span>
              <?php endif; ?>
            <?php endif; ?>

            <?= insert('/_block/admin-dropdown-post', ['post' => $post]); ?>
          <?php endif; ?>
        </div>

      <?php if ($post['post_thumb_img']) : ?>
        <?= Img::image($post['post_thumb_img'], $post['post_title'],  'thumb right ml15', 'post', 'thumbnails'); ?>
      <?php endif; ?>

      <div class="max-w780">
        <div class="content-body">
          <?= markdown($post['post_content'], 'text'); ?>
        </div>
        <?php if ($post['post_url_domain']) : ?>
          <div class="mb15">
            <a rel="nofollow noreferrer ugc" target="_blank" class="btn btn-primary" href="<?= $post['post_url']; ?>">
              <?= __('app.details_here'); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ($post['post_url_domain']) : ?>
          <?= __('app.source'); ?>:
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#link"></use>
          </svg>
          <a class="gray" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
            <?= $post['post_url_domain']; ?>
          </a>
      </div>
    <?php endif; ?>
    <?= insert('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>
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

    <?php if (UserData::checkActiveUser()) : ?>
      <?php if ($post['post_feature'] == 0 && $post['post_draft'] == 0 && $post['post_closed'] == 0) : ?>

        <form action="<?= url('content.create', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
          <?= csrf_field() ?>

          <?= insert('/_block/form/editor', ['height'  => '250px', 'type' => 'answer', 'id' => $post['post_id']]); ?>

          <div class="clear mb15">
            <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
            <input type="hidden" name="comment_id" value="0">
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
  <div class="ml10">
    <?php if ($post['post_draft'] == 0) :
      if ($post['post_feature'] == 0) :
        insert('/content/post/format-discussion', ['data' => $data, 'post' => $post]);
      else :
        insert('/content/post/format-qa', ['data' => $data, 'post' => $post]);
      endif;
    else :
      echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.this_draft'), 'icon' => 'closed']);
    endif; ?>
  </div>
</main>
<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    mediumZoom(document.querySelectorAll('.content img:not(.emoji), .post img:not(.emoji), .content-body p img:not(.emoji)'));
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
<?= insert('/_block/dialog/msg-flag'); ?>