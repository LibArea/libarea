<main class="col-span-9 mb-col-12">
  <article class="post-full border-box-1 br-rd-5 bg-white<?php if ($data['post']['post_is_deleted'] == 1) { ?> bg-red-300<?php } ?> pt0 pr5 pb5 pl15">
    <?php if ($data['post']['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
      <div class="post-body">
        <h1 class="title mb0 size-24">
          <?= $data['post']['post_title']; ?>
          <?php if ($data['post']['post_is_deleted'] == 1) { ?>
            <i class="bi bi-trash blue"></i>
          <?php } ?>
          <?php if ($data['post']['post_closed'] == 1) { ?>
            <i class="bi bi-lock gray"></i>
          <?php } ?>
          <?php if ($data['post']['post_top'] == 1) { ?>
            <i class="bi bi-pin-angle blue"></i>
          <?php } ?>
          <?php if ($data['post']['post_lo'] > 0) { ?>
            <i class="bi bi-award blue"></i>
          <?php } ?>
          <?php if ($data['post']['post_type'] == 1) { ?>
            <i class="bi bi-patch-question green"></i>
          <?php } ?>
          <?php if ($data['post']['post_translation'] == 1) { ?>
            <span class="pt5 pr10 pb5 pl10 gray-light bg-yellow-100 br-rd-3 size-14 italic lowercase">
              <?= lang('translation'); ?>
            </span>
          <?php } ?>
          <?php if ($data['post']['post_tl'] > 0) { ?>
            <span class="pt5 pr10 pb5 pl10 gray-light bg-orange-100 br-rd-3 italic size-14">
              tl<?= $data['post']['post_tl']; ?>
            </span>
          <?php } ?>
          <?php if ($data['post']['post_merged_id'] > 0) { ?>
            <i class="link-link-ext blue"></i>
          <?php } ?>
        </h1>
        <div class="size-14 lowercase flex gray-light-2">
          <a class="gray" href="<?= getUrlByName('user', ['login' => $data['post']['user_login']]); ?>">
            <?= user_avatar_img($data['post']['user_avatar'], 'small', $data['post']['user_login'], 'w18'); ?>
            <span class="mr5 ml5">
              <?= $data['post']['user_login']; ?>
            </span>
          </a>
          <span class="blue mr10 ml5"><i class="bi bi-mic size-14"></i></span>
          <?= $data['post']['post_date_lang']; ?>
          <?php if ($data['post']['modified']) { ?>
            (<?= lang('ed'); ?>)
          <?php } ?>

          <?php if ($uid['user_id']) { ?>
            <?php if ($uid['user_login'] == $data['post']['user_login']  || $uid['user_trust_level'] == 5) { ?>
              <a class="gray-light mr10 ml10" href="/post/edit/<?= $data['post']['post_id']; ?>">
                <?= lang('edit'); ?>
              </a>
            <?php } ?>
            <?php if ($uid['user_login'] == $data['post']['user_login']) { ?>
              <?php if ($data['post']['post_draft'] == 0) { ?>
                <?php if ($data['post']['user_my_post'] == $data['post']['post_id']) { ?>
                  <span class="mu_post gray-light mr10 ml10">+ <?= lang('in-the-profile'); ?></span>
                <?php } else { ?>
                  <a class="add-post-profile gray-light mr10 ml10" data-post="<?= $data['post']['post_id']; ?>">
                    <span class="mu_post"><?= lang('in-the-profile'); ?></span>
                  </a>
                <?php } ?>
              <?php } ?>
            <?php } ?>
            <?php if ($uid['user_trust_level'] == 5) { ?>
              <a data-type="post" data-id="<?= $data['post']['post_id']; ?>" class="type-action gray-light mr10 ml10">
                <?php if ($data['post']['post_is_deleted'] == 1) { ?>
                  <?= lang('recover'); ?>
                <?php } else { ?>
                  <?= lang('remove'); ?>
                <?php } ?>
              </a>
              <span class="size-14 mr5 ml10">
                <?= $data['post']['post_hits_count']; ?>
              </span>
            <?php } ?>
            <?= includeTemplate('/_block/show-ip', ['ip' => $data['post']['post_ip'], 'user_trust_level' => $uid['user_trust_level']]); ?>
          <?php } ?>
        </div>
      </div>
      <?php if ($data['post']['post_thumb_img']) { ?>
        <?= post_img($data['post']['post_thumb_img'], $data['post']['post_title'],  'thumb right', 'thumbnails'); ?>
      <?php } ?>
      <div class="post-body max-w780 full">
        <div class="post">
          <?= $data['post']['post_content']; ?>
        </div>

        <?php if ($data['post']['post_url_domain']) { ?>
          <div class="mb15">
            <a rel="nofollow noreferrer ugc" target="_blank" class="button br-rd-5 white" href="<?= $data['post']['post_url']; ?>">
              <?= lang('details are here'); ?> <i class="bi bi-folder-symlink ml5"></i>
            </a>
          </div>
        <?php } ?>

        <?php if ($data['lo']) { ?>
          <div class="bg-yellow-100 pt5 pr5 pb5 pl10 mt10 mb10">
            <h3 class="uppercase mb10 pt10 pb10 font-normal">ЛО
              <span class="right">
                <a rel="nofollow" href="<?= getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>#answer_<?= $data['lo']['answer_id']; ?>">
                  <i class="bi bi-award red"></i>
                </a>
              </span>
            </h3>
            <?= $data['lo']['answer_content']; ?>
          </div>
        <?php } ?>
        <?php if ($data['post']['post_url_domain']) { ?>
          <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('website'); ?></h3>
          <div class="italic m15 mb15 p10 size-14 bg-gray-100 table gray">
            <div>
              <i class="bi bi-link-45deg"></i>
              <a class="gray" href="<?= getUrlByName('domain', ['domain' => $data['post']['post_url_domain']]); ?>">
                <?= $data['post']['post_url_domain']; ?>
              </a>
            </div>
          </div>
        <?php } ?>
        <?php if (!empty($data['post_related'])) { ?>
          <div class="mb20">
            <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('related'); ?></h3>
            <?php $num = 0; ?>
            <?php foreach ($data['post_related'] as $related) { ?>
              <div class="mb5 flex">
                <?php $num++; ?>
                <div class="flex justify-center bg-gray-200 w21 mr5 br-rd-50 size-15">
                  <span class="gray-light-2"><?= $num; ?></span>
                </div>
                <a href="<?= getUrlByName('post', ['id' => $related['post_id'], 'slug' => $related['post_slug']]); ?>">
                  <?= $related['post_title']; ?>
                </a>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
        <?php if (!empty($data['topics'])) { ?>
          <div class="mb20">
            <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('topics'); ?>:</h3>
            <?php foreach ($data['topics'] as $topic) { ?>
              <a class="bg-blue-100 bg-hover-300 white-hover flex justify-center pt5 pr10 pb5 pl10 br-rd-20 blue inline size-14" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
                <?= $topic['topic_title']; ?>
              </a>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
      <div class="border-bottom flex flex-row items-center justify-between mb5 pt10">
        <div class="flex flex-row items-center">
          <?= votes($uid['user_id'], $data['post'], 'post'); ?>
          <?php $cout = $data['post']['post_answers_count'] + $data['post']['post_comments_count']; ?>

          <span class="right ml15 gray-light-2">
            <i class="bi bi-chat-dots"></i>
            <?php if ($cout > 0) { ?>
              <?= $data['post']['post_answers_count'] + $data['post']['post_comments_count']; ?>
            <?php } ?>
          </span>
        </div>
        <div class="flex flex-row items-center">
          <?= favorite_post($uid['user_id'], $data['post']['post_id'], $data['post']['favorite_tid']); ?>
        </div>
      </div>
      <div class="hidden">
        <?php if (!$uid['user_id']) { ?>
          <a class="right size-14 mt5 add-focus br-rd-20 center pt5 pr15 pb5 pl15" href="<?= getUrlByName('login'); ?>">
            + <?= lang('Read'); ?>
          </a>
        <?php } else { ?>
          <?php if (is_array($data['post_signed'])) { ?>
            <div data-id="<?= $data['post']['post_id']; ?>" data-type="post" class="focus-id size-14 right mt5 del-focus br-rd-20 center pt5 pr15 pb5 pl15">
              <?= lang('unsubscribe'); ?>
            </div>
          <?php } else { ?>
            <div data-id="<?= $data['post']['post_id']; ?>" data-type="post" class="focus-id size-14 right mt5 add-focus br-rd-20 center pt5 pr15 pb5 pl15">
              + <?= lang('read'); ?>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
      <div>
        <?php if ($data['post']['post_type'] == 0 && $data['post']['post_draft'] == 0) { ?>
          <?php if ($uid['user_id'] > 0) { ?>
            <?php if ($data['post']['post_closed'] == 0) { ?>
              <?= includeTemplate('/_block/editor/answer-create-editor', ['post_id' => $data['post']['post_id'], 'type' => 'answer']); ?>
            <?php } ?>
          <?php } else { ?>
            <textarea rows="5" class="bg-gray-000 mt15" disabled="disabled" placeholder="<?= lang('no-auth-comm'); ?>" name="answer" id="answer"></textarea>
            <div>
              <input type="submit" name="answit" value="<?= lang('reply'); ?>" class="button block br-rd-5 white" disabled="disabled">
            </div>
          <?php } ?>
        <?php } else { ?>
          <!-- draft -->
        <?php } ?>
      </div>
    <?php } else { ?>
      <div class="bg-red-300 p15 center mr10">
        <?= lang('post deleted'); ?>...
      </div>
    <?php } ?>
  </article>

  <?php if ($data['post']['post_draft'] == 0) { ?>
    <?php if ($data['post']['post_type'] == 0) { ?>
      <?= includeTemplate('/_block/comments-view', ['data' => $data, 'uid' => $uid]); ?>
      <?php if ($data['post']['post_closed'] == 1) { ?>
        <?= includeTemplate('/_block/no-content', ['lang' => 'the post is closed']); ?>
      <?php } ?>
    <?php } else { ?>
      <?= includeTemplate('/_block/questions-view', ['data' => $data, 'uid' => $uid]); ?>
      <?php if ($data['post']['post_closed'] == 1) { ?>
        <?= includeTemplate('/_block/no-content', ['lang' => 'the question is closed']); ?>
      <?php } ?>
    <?php } ?>
  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'this is a draft']); ?>
  <?php } ?>
</main>
<aside class="col-span-3 br-rd-5 no-mob">
  <?= includeTemplate('/_block/space-info-sidebar', ['data' => $data['post']]); ?>
  <?php if ($data['post']['post_content_img']) { ?>
    <div class="border-box-1 bg-white br-rd-5 mb15">
      <div id="layer-photos" class="layer-photos p15">
        <?= post_img($data['post']['post_content_img'], $data['post']['post_title'], 'w-100 br-rd-5', 'cover', $data['post']['post_content_img']); ?>
      </div>
    </div>
  <?php } ?>
  <div class="border-box-1 bg-white br-rd-5 mb15 p15">
    <div class="social center" data-url="<?= Config::get('meta.url') . getUrlByName('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>" data-title="<?= $data['post']['post_title']; ?>">
      <a class="size-21 pl15 pr15 gray-light-2" data-id="fb"><i class="bi bi-facebook"></i></a>
      <a class="size-21 pl15 pr15 gray-light-2" data-id="vk">VK</a>
      <a class="size-21 pl15 pr15 gray-light-2" data-id="tw"><i class="bi bi-twitter"></i></a>
    </div>
  </div>

  <?php if ($data['recommend']) { ?>
    <div class="border-box-1 bg-white br-rd-5 mb15 post-view sticky recommend p15">
      <h3 class="uppercase mb10 mt0 font-light size-14 gray"><?= lang('recommended'); ?></h3>
      <?php foreach ($data['recommend'] as  $rec_post) { ?>
        <div class="mb15 hidden flex">
          <a class="gray size-15" href="<?= getUrlByName('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
            <?php if ($rec_post['post_answers_count'] > 0) { ?>
              <div class="p5 pr10 pb5 pl10 bg-green-400 br-rd-3 white size-14 center mr15">
                <?= $rec_post['post_answers_count'] ?>
              </div>
            <?php } else { ?>
              <div class="p5 pr10 pb5 pl10 bg-gray-300 br-rd-3 gray size-14 center mr15">0</div>
            <?php } ?>
          </a>
          <a class="black size-14" href="<?= getUrlByName('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
            <?= $rec_post['post_title']; ?>
          </a>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
</aside>
<script nonce="<?= $_SERVER['nonce']; ?>">
  $(document).ready(function() {
    layer.photos({
      photos: '#layer-photos',
      anim: 4
    });
    $(document).on('click', '.msg-flag', function() {
      let post_id = $(this).data('post_id');
      let content_id = $(this).data('content_id');
      let type = $(this).data('type');
      layer.confirm('<?= lang('does this violate site rules'); ?>?', {
        icon: 5,
        title: '<?= lang('report'); ?>',
        btn: ['<?= lang('yes'); ?>', '<?= lang('No'); ?>']
      }, function(index) {
        $.post('/flag/repost', {
          type,
          post_id,
          content_id
        }, function(str) {
          if (str == 1) {
            layer.msg('<?= lang('flag not included'); ?>!');
            return false;
          }
          layer.msg('<?= lang('thanks'); ?>!');
        });
      });
    });
  });
</script>
<?= includeTemplate('/_block/wide-footer'); ?>