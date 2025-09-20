<?php
$item = $data['contents'];
$blog = $data['blog'][0] ?? null;
?>

<main>
  <article<?php if ($item['post_is_deleted'] == 1) : ?> class="bg-red-200" <?php endif; ?>>
    <?php if ($item['post_is_deleted'] == 0 || $container->user()->admin()) : ?>
      <?php if (!empty($data['united'])) : ?>
        <div class="box bg-yellow mb15 gray-600">
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#git-merge"></use>
          </svg>
          <?= __('app.post_merged'); ?>
          <?php foreach ($data['united'] as $merged) : ?>
            (id <?= $merged['post_id']; ?>):
            <?php if ($container->user()->admin()) : ?>
              <a href="/post/<?= $merged['post_id']; ?>"><?= $merged['post_title']; ?></a>
            <?php else : ?>
              <?= $merged['post_title']; ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="uppercase-box">
        <?= insert('/content/publications/type-publication', ['type' => $item['post_type']]); ?>
        <?php if ($item['post_type'] == 'post') : ?>
          <div class="right"><?= insert('/content/publications/title', ['item' => $item]); ?></div>
        <?php endif; ?>
      </div>

      <div class="user-info">
        <a title="<?= $item['login']; ?>" href="<?= url('profile', ['login' => $item['login']]); ?>">
          <?= Img::avatar($item['avatar'], $item['login'], 'img-sm mr5', 'small'); ?>
          <span class="nickname<?php if (Html::loginColor($item['created_at'] ?? false)) : ?> new<?php endif; ?>"><?= $item['login']; ?></span>
        </a>

        <span class="lowercase">
          <?= langDate($item['post_date']); ?>
        </span>

        <?php if ($container->user()->active()) : ?>
          <?= insert('/_block/admin-dropdown-content', ['item' => $item]); ?>
        <?php endif; ?>
      </div>

      <h1 class="m0 mb5"><?= $item['post_title']; ?>
        <?php if ($item['post_type'] != 'post') : ?>
          <?= insert('/content/publications/title', ['item' => $item]); ?>
        <?php endif; ?>
      </h1>

      <?php if ($item['post_thumb_img']) : ?>
        <div class="img-preview mt10">
          <?= Img::image($item['post_thumb_img'], $item['post_title'],  'zooom', 'post', 'thumbnails'); ?>
        </div>
      <?php endif; ?>

      <div class="max-w-md">
        <div class="content-body mb15">
          <?php $markdown = markdown($item['post_content'], 'text');
          if (Html::headings($markdown, post_slug($item['post_type'], $item['post_id'], $item['post_slug'])) == false) : ?>

            <?= $markdown; ?>

          <?php else : ?>

            <?php $content = Html::headings($markdown, post_slug($item['post_type'], $item['post_id'], $item['post_slug'])); ?>

            <div class="mt20">
              <b><?= __('app.headings'); ?></b>
              <?= $content['head']; ?>
            </div>

            <?= $content['text']; ?>
          <?php endif; ?>
        </div>
        <?php if ($item['post_url_domain']) : ?>
          <div class="mb15">
            <a rel="nofollow noreferrer ugc" target="_blank" class="btn btn-outline-primary" href="<?= $item['post_url']; ?>">
              <?= __('app.details_here'); ?>
            </a>
          </div>
        <?php endif; ?>
        <?php if ($item['post_url_domain']) : ?>
          <div class="italic mb15 text-sm table gray">
            <?= __('app.source'); ?>:
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#link"></use>
            </svg>
            <a class="gray" href="<?= url('domain', ['domain' => $item['post_url_domain']]); ?>">
              <?= $item['post_url_domain']; ?>
            </a>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($item['post_poll']) : ?>
        <?= insert('/content/poll/poll', ['poll' => $data['poll']]); ?>
      <?php endif; ?>

      <div class="flex flex-row items-center text-sm mt15">
        <?php if (!empty($data['facets'])) : ?>
          <svg class="icon gray-600">
            <use xlink:href="/assets/svg/icons.svg#hash"></use>
          </svg>
          <?php foreach ($data['facets'] as $topic) : ?>
            <a class="gray-600 mr15 lowercase" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>"><?= $topic['facet_title']; ?></a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <?php if (!empty($data['blog'])) : ?>
        <div class="flex flex-row items-center mt15 text-sm">
          <span class="gray-600 mr5 mb-none"><?= __('app.published'); ?> </span>
          <a title="<?= $blog['facet_title']; ?>" class="bg-white p5 brown" href="/blog/<?= $blog['facet_slug']; ?>">
            <?= $blog['facet_title']; ?>
          </a>
        </div>
      <?php endif; ?>

      <div class="mt15 mb15 items-center flex justify-between">
        <div class="items-center flex gap gray-600">
          <?= Html::votes($item, 'post'); ?>
          <div class="items-center flex gap-sm mb-none">
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#eye"></use>
            </svg>
            <?= $item['post_hits_count'] == 0 ? 1 : Html::formatToHuman($item['post_hits_count']); ?>
          </div>
        </div>
        <div class="items-center flex gap-lg">
          <?php if ($container->user()->active()) : ?>
            <?php if (is_array($data['post_signed'])) : ?>
              <div data-id="<?= $item['post_id']; ?>" data-type="post" class="focus-id right mt5 gray-600">
                <?= __('app.unsubscribe'); ?>
              </div>
            <?php else : ?>
              <div data-id="<?= $item['post_id']; ?>" data-type="post" class="focus-id right mt5 red">
                + <?= __('app.read'); ?>
              </div>
            <?php endif; ?>
          <?php else : ?>
            <a class="right mt5 focus-id red" href="<?= url('login'); ?>">
              + <?= __('app.read'); ?>
            </a>
          <?php endif; ?>

          <div class="pointer" data-a11y-dialog-show="id-share">
            <svg class="icon gray-600">
              <use xlink:href="/assets/svg/icons.svg#share"></use>
            </svg>
          </div>
          <?php if ($item['post_draft'] == 0) : ?>
            <?= Html::favorite($item['post_id'], 'post', $item['tid']); ?>
          <?php endif; ?>
        </div>
      </div>

      <?= insert('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>

      <?php
      $is_comments = 0;
      foreach ($data['facets'] as $ind => $facet) : ?>
        <?php if ($facet['facet_is_comments'] != 0) {
          $is_comments = $facet['facet_is_comments'];
        } ?>
      <?php endforeach; ?>

      <?php if (empty($is_comments)) : ?>

        <?php if ($container->user()->active()) : ?>
          <?php if ($item['post_type'] != 'question' && $item['post_draft'] == 0 && $item['post_closed'] == 0) : ?>

            <form action="<?= url('add.comment', method: 'post'); ?>" accept-charset="UTF-8" method="post">
              <?= $container->csrf()->field(); ?>

              <?= insert('/_block/form/editor/notoolbar-img', ['height'  => '170px', 'type' => 'comment', 'id' => $item['post_id'], 'title' => __('app.reply')]); ?>

              <div class="clear mt5">
                <input type="hidden" name="post_id" value="<?= $item['post_id']; ?>">
                <input type="hidden" name="comment_id" value="0">
                <?= Html::sumbit(__('app.reply')); ?>
              </div>
            </form>

          <?php endif; ?>
        <?php endif; ?>

      <?php endif; ?>

    <?php else : ?>
      <div class="box center bg-red-200">
        <?= __('app.content_deleted', ['name' => __('app.post')]); ?>...
      </div>
    <?php endif; ?>
    </article>

    <?php if (empty($is_comments)) : ?>
      <div id="comment"></div>
      <?php if ($item['post_draft'] == 0) :
        if ($item['post_type'] == 'question') :
          insert('/content/publications/format-qa', ['data' => $data, 'item' => $item]);
        else :
          insert('/content/publications/format-discussion', ['data' => $data, 'item' => $item]);
        endif;
      else :
        echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.this_draft'), 'icon' => 'lock']);
      endif; ?>
    <?php else : ?>
      <?php insert('/_block/no-content', ['type' => 'small', 'text' => __('app.topic_comments_disabled'), 'icon' => 'lock']); ?>
    <?php endif; ?>
</main>

<aside>
  <?php if (!empty($data['facets'])) : ?>
    <div class="box">
      <h4 class="uppercase-box"><?= __('app.topics'); ?></h4>
      <?php foreach ($data['facets'] as $topic) : ?>
        <div class="flex justify-between items-center">
          <div class="mt5 mb5">
            <?= Img::image($topic['facet_img'], $topic['facet_title'], 'img-base mr5', 'logo', 'max'); ?>
            <a title="<?= $topic['facet_title']; ?>" class="black text-sm" href="<?= url('topic', ['slug' => $topic['facet_slug']]); ?>">
              <?= $topic['facet_title']; ?>
            </a>
          </div>
          <?php if (!$topic['signed_facet_id'] && $container->user()->id()) : ?>
            <div data-id="<?= $topic['facet_id']; ?>" data-type="facet" class="focus-id right inline text-sm red">
              <?= __('app.read'); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($item['post_content_img']) : ?>
    <div class="box br-lightgray img-preview">
      <img class="w-100" src="<?= Img::PATH['posts_cover'] . $item['post_content_img']; ?>" alt="<?= $item['post_title']; ?>">
    </div>
  <?php endif; ?>

  <?php if ($data['similar'] && $item['post_is_deleted'] != 1) : ?>
    <div class="box sticky">
      <h4 class="uppercase-box"><?= __('app.recommended'); ?></h4>
      <?php foreach ($data['similar'] as  $rec) : ?>
        <?php $url =  (config('general', 'search_engine') == true) ? $rec['url'] : post_slug($rec['post_type'], $rec['post_id'], $rec['post_slug']);  ?>
        <a class="black mb20 block" href="<?= $url; ?>">
          <?= $rec['title']; ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <!-- мой блог -->
  <?php endif; ?>
</aside>
<script nonce="<?= config('main', 'nonce'); ?>">
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
          comment.innerHTML = '> ' + text + '\r\n';
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

<script src="/assets/js/dialog/dialog.js"></script>
<?= insert('/_block/dialog/msg-flag'); ?>
<?= insert('/_block/dialog/share', ['title' => __('app.share_post'), 'url' => config('meta', 'url') . post_slug($item['post_type'], $item['post_id'], $item['post_slug'])]); ?>