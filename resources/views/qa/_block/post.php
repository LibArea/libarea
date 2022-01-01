<?php if (!empty($data['posts'])) { ?>
  <?php $n = 0;
  foreach ($data['posts'] as $post) {
    $n++; ?>
    <?php if ($uid['user_id'] == 0 && $n == 6) { ?>
      <?= import('/_block/no-login-screensaver', ['uid' => $uid]); ?>
    <?php } ?>
    <?php $post_url = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>
    <div class="grid col-span-12 flex br-bottom p10 mb5 mt5 article_<?= $post['post_id']; ?>">
      <div class="col-span-2 flex mb-inline mr15">
        <div class="box-answer br-gray-200 block bg-gray-100 gray-600 mt5 br-rd3 lowercase mr15">
          <?= $post['post_votes']; ?>
          <div class="text-xs"> <?= num_word($post['post_votes'], Translate::get('num-up'), false); ?></div>
        </div>
        <?php $bg = $post['post_feature'] == 0 ? ' bg-green-700' : ' bg-pink-800'; ?>
        <?php $bg_url = $post['post_url_domain'] == NULL ? '' : ' bg-blue-500'; ?>
        <div class="box-answer mt5 br-rd3 lowercase <?= $bg; ?> <?= $bg_url; ?>">
          <a class="block white white-hover" href="<?= $post_url; ?>#comment">
            <?php $anw = $post['post_answers_count'] + $post['post_comments_count'];
            echo $anw; ?>
          </a>
          <div class="text-xs white"> <?= num_word($anw, Translate::get('num-answer'), false); ?></div>
        </div>
      </div>

      <div class="col-span-10 w-100 mr15">
        <div class="mt0 mb0">
        <?php if ($bg_url) { ?>
          <span><?= Translate::get('news'); ?>:</span>
        <?php } ?>
        <a href="<?= $post_url; ?>">
          <span class="font-normal text-xl">
            <?= import('/_block/post-title', ['post' => $post, 'uid' => $uid]); ?>
          </span>
        </a>
        </div>
        <div class="flex flex-row flex-auto items-center justify-between lowercase">
          <div class="flex-auto">
            <?= html_facet($post['facet_list'], 'blog', 'gray text-xs mr15'); ?>
            <?= html_facet($post['facet_list'], 'topic', 'tags-xs'); ?>
            <?php if ($post['post_url_domain']) { ?>
              <a class="gray-600 text-sm ml10" href="<?= getUrlByName('domain', ['domain' => $post['post_url_domain']]); ?>">
                <i class="bi bi-link-45deg middle"></i> <?= $post['post_url_domain']; ?>
              </a>
            <?php } ?>
          </div>

          <div class="gray-600 text-xs">
            <?= $post['post_date'] ?> ·
            <?= num_word($post['post_hits_count'], Translate::get('num-view'), true); ?> ·
            <a class="dark-gray-300" href="<?= getUrlByName('user', ['login' => $post['user_login']]); ?>">
              <?= $post['user_login']; ?>
            </a>
          </div>
        </div>

        <?php if ($data['sheet'] == 'subscribed') { ?>
          <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id text-sm right">
            <?= Translate::get('unsubscribe'); ?>
          </div>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
<?php } else { ?>
  <?= import('/_block/recommended-topics', ['data' => $data, 'uid' => $uid]); ?>
  <div class="mt10 mb10 pt10 pr15 pb10 center pl15 bg-yellow-100 gray">
    <?= Translate::get('there are no posts'); ?>...
  </div>
<?php } ?>