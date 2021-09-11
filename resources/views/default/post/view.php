<div class="wrap">
  <main>
    <article class="post-full">
      <?php if ($data['post']['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
        <div class="white-box pt10 pr15 pb15 pl15<?php if ($data['post']['post_is_deleted'] == 1) { ?> bg-red-300<?php } ?>">
          <div class="post-body">
            <h1 class="title size-24">
              <?= $data['post']['post_title']; ?>
              <?php if ($data['post']['post_is_deleted'] == 1) { ?>
                <i class="icon-trash-empty blue"></i>
              <?php } ?>
              <?php if ($data['post']['post_closed'] == 1) { ?>
                <i class="icon-closed"></i>
              <?php } ?>
              <?php if ($data['post']['post_top'] == 1) { ?>
                <i class="icon-pin-outline blue"></i>
              <?php } ?>
              <?php if ($data['post']['post_lo'] > 0) { ?>
                <i class="icon-diamond blue"></i>
              <?php } ?>
              <?php if ($data['post']['post_type'] == 1) { ?>
                <i class="icon-help green"></i>
              <?php } ?>
              <?php if ($data['post']['post_translation'] == 1) { ?>
                <span class="translation size-13 italic lowercase"><?= lang('Translation'); ?></span>
              <?php } ?>
              <?php if ($data['post']['post_tl'] > 0) { ?>
                <span class="trust-level italic size-13">tl<?= $data['post']['post_tl']; ?></span>
              <?php } ?>
              <?php if ($data['post']['post_merged_id'] > 0) { ?>
                <i class="link-link-ext blue"></i>
              <?php } ?>
            </h1>
            <div class="size-13 lowercase flex gray-light">
              <a class="gray" href="/u/<?= $data['post']['user_login']; ?>">
                <?= user_avatar_img($data['post']['user_avatar'], 'small', $data['post']['user_login'], 'ava'); ?>
                <span class="mr5 ml5">
                  <?= $data['post']['user_login']; ?>
                </span>
              </a>
              <span class="gray-light">
                <?= $data['post']['post_date_lang']; ?>
                <?php if ($data['post']['modified']) { ?>
                  (<?= lang('ed'); ?>)
                <?php } ?>
              </span>
              <?php if ($uid['user_id']) { ?>
                <?php if ($uid['user_login'] == $data['post']['user_login']  || $uid['user_trust_level'] == 5) { ?>
                  <a class="gray-light mr10 ml10" href="/post/edit/<?= $data['post']['post_id']; ?>">
                    <?= lang('Edit'); ?>
                  </a>
                <?php } ?>
                <?php if ($uid['user_login'] == $data['post']['user_login']) { ?>
                  <?php if ($data['post']['post_draft'] == 0) { ?>
                    <?php if ($data['post']['user_my_post'] == $data['post']['post_id']) { ?>
                      <span class="mu_post gray-light mr10 ml10">+ <?= lang('in-the-profile'); ?></span>
                    <?php } else { ?>
                      <a class="user-mypost gray-light mr10 ml10" data-opt="1" data-post="<?= $data['post']['post_id']; ?>">
                        <span class="mu_post"><?= lang('in-the-profile'); ?></span>
                      </a>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
                <span class="add-favorite gray-light mr10 ml10" data-id="<?= $data['post']['post_id']; ?>" data-type="post">
                  <?php if (is_array($data['post']['favorite_post'])) { ?>
                    <?= lang('remove-favorites'); ?>
                  <?php } else { ?>
                    <?= lang('add-favorites'); ?>
                  <?php } ?>
                </span>
                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <a data-type="post" data-id="<?= $data['post']['post_id']; ?>" class="type-action gray-light mr10 ml10">
                    <?php if ($data['post']['post_is_deleted'] == 1) { ?>
                      <?= lang('Recover'); ?>
                    <?php } else { ?>
                      <?= lang('Remove'); ?>
                    <?php } ?>
                  </a>
                  <span class="size-13 mr5 ml10">
                    <?= $data['post']['post_hits_count']; ?>
                  </span>
                <?php } ?>
                <?= content_ip($data['post']['post_ip'], $uid); ?>
              <?php } ?>
            </div>
          </div>
          <?php if ($data['post']['post_thumb_img']) { ?>
            <?= post_img($data['post']['post_thumb_img'], $data['post']['post_title'],  'thumb right', 'thumbnails'); ?>
          <?php } ?>
          <div class="post-body full">
            <div class="post">
              <?= $data['post']['post_content']; ?>
            </div>
            
            <?php if ($data['post']['post_url_domain']) { ?>
              <div class="mb15">
               <a rel="nofollow noreferrer ugc" target="_blank" class="button" href="<?= $data['post']['post_url']; ?>">
                  <?= lang('Details are here'); ?> <i class="icon-link-ext ml5"></i>
               </a>
              </div>
            <?php } ?>
            
            <?php if ($data['lo']) { ?>
              <div class="bg-yellow-100 pt5 pr5 pb5 pl10 mt10 mb10">
                <h3 class="recommend">ЛО
                  <span class="right">
                    <a rel="nofollow" href="<?= post_url($data['post']); ?>#answer_<?= $data['lo']['answer_id']; ?>">
                      <i class="icon-diamond red"></i>
                    </a>
                  </span>
                </h3>
                <?= $data['lo']['answer_content']; ?>
              </div>
            <?php } ?>
            <?php if ($data['post']['post_url_domain']) { ?>
              <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Website'); ?></h3>
              <div class="italic m15 mb15 p10 size-13 bg-gray-100 table gray">
                <div>
                  <i class="icon-link"></i>
                  <a class="gray" href="/domain/<?= $data['post']['post_url_domain']; ?>">
                    <?= $data['post']['post_url_domain']; ?>
                  </a>
                </div>
              </div>
            <?php } ?>
            <?php if (!empty($data['post_related'])) { ?>
              <div class="mb20">
                <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Related'); ?></h3>
                <?php $num = 0; ?>
                <?php foreach ($data['post_related'] as $related) { ?>
                  <div class="mb5">
                    <?php $num++; ?>
                    <span class="related-count gray-light size-15"><?= $num; ?></span>
                    <a href="<?= post_url($related); ?>">
                      <?= $related['post_title']; ?>
                    </a>
                  </div>
                <?php } ?>
              </div>
            <?php } ?>
            <?php if (!empty($data['topics'])) { ?>
              <div class="mb20">
                <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Topics'); ?>:</h3>
                <?php foreach ($data['topics'] as $topic) { ?>
                  <a class="tags gray size-13" href="/topic/<?= $topic['topic_slug']; ?>">
                    <?= $topic['topic_title']; ?>
                  </a>
                <?php } ?>
              </div>
            <?php } ?>
          </div>
          <div class="border-bottom mb10 pb5 hidden flex justify-content-between gray">
            <?= votes($uid['user_id'], $data['post'], 'post'); ?>
            <span class="right gray-light">
              <i class="icon-commenting-o middle"></i>
              <?= $data['post']['post_answers_count'] + $data['post']['post_comments_count'] ?>
            </span>
          </div>
          <div class="hidden">
            <?php if (!$uid['user_id']) { ?>
              <a class="right size-13 mt5 add-focus focus-topic" href="/login">
                + <?= lang('Read'); ?>
              </a>
            <?php } else { ?>
              <?php if (is_array($data['post_signed'])) { ?>
                <div data-id="<?= $data['post']['post_id']; ?>" data-type="post" class="focus-id size-13 right mt5 del-focus focus-topic">
                  <?= lang('Unsubscribe'); ?>
                </div>
              <?php } else { ?>
                <div data-id="<?= $data['post']['post_id']; ?>" data-type="post" class="focus-id size-13 right mt5 add-focus focus-topic">
                  + <?= lang('Read'); ?>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
          <div>
            <?php if ($data['post']['post_type'] == 0 && $data['post']['post_draft'] == 0) { ?>
              <?php if ($uid['user_id'] > 0) { ?>
                <?php if ($data['post']['post_closed'] == 0) { ?>
                  <form id="add_answ" class="new_answer mt15" action="/answer/create" accept-charset="UTF-8" method="post">
                    <?= csrf_field() ?>
                    <div id="test-markdown-view-post">
                      <textarea minlength="6" class="md-post" rows="5" placeholder="<?= lang('write-something'); ?>..." name="answer" id="wmd-input"></textarea>
                    </div>
                    <div class="boxline">
                      <input type="hidden" name="post_id" id="post_id" value="<?= $data['post']['post_id']; ?>">
                      <input type="hidden" name="answer_id" id="answer_id" value="0">
                      <input type="submit" class="button" name="answit" value="<?= lang('Reply'); ?>" class="button">
                    </div>
                  </form>
                <?php } ?>
              <?php } else { ?>
                <textarea rows="5" class="bg-gray-000 mt15" disabled="disabled" placeholder="<?= lang('no-auth-comm'); ?>" name="answer" id="answer"></textarea>
                <div>
                  <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="button" disabled="disabled">
                </div>
              <?php } ?>
            <?php } else { ?>
              <!-- draft -->
            <?php } ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="bg-red-300">
          <?= lang('Post deleted'); ?>...
        </div>
      <?php } ?>

      <?php if ($data['post']['post_draft'] == 0) { ?>
        <?php if ($data['post']['post_type'] == 0) { ?>
          <?php includeTemplate('/post/comment-view', ['data' => $data, 'uid' => $uid]); ?>
          <?php if ($data['post']['post_closed'] == 1) { ?>
            <?= no_content('The post is closed'); ?>
          <?php } ?>
        <?php } else { ?>
          <?php includeTemplate('/post/questions-view', ['data' => $data, 'uid' => $uid]); ?>
          <?php if ($data['post']['post_closed'] == 1) { ?>
            <?= no_content('The question is closed'); ?>
          <?php } ?>
        <?php } ?>
      <?php } else { ?>
        <?= no_content('This is a draft'); ?>
      <?php } ?>
    </article>
  </main>

  <aside>
    <div class="white-box pt5 pr15 pb5 pl15">
      <div class="mt10 mb10">
        <a class="flex" title="<?= $data['post']['space_name']; ?>" href="/s/<?= $data['post']['space_slug']; ?>">
          <?= spase_logo_img($data['post']['space_img'], 'max', $data['post']['space_slug'], 'ava-24 mr5'); ?>
          <span class="ml5"><?= $data['post']['space_name']; ?></span>
        </a>
      </div>
      <div class="gray size-13"><?= $data['post']['space_short_text']; ?></div>
    </div>
    <?php if ($data['post']['post_content_img']) { ?>
      <div class="white-box">
        <div id="layer-photos" class="layer-photos p15">
          <?= post_img($data['post']['post_content_img'], $data['post']['post_title'], 'img-post', 'cover', $data['post']['post_content_img']); ?>
        </div>
      </div>
    <?php } ?>
    <div class="white-box pt0 pr15 pb5 pl15">
      <h3 class="recommend size-13"><?= lang('To share'); ?></h3>
      <div class="social center" data-url="<?= Lori\Config::get(Lori\Config::PARAM_URL) . '/post/' . $data['post']['post_id'] . '/' . $data['post']['post_slug']; ?>" data-title="<?= $data['post']['post_title']; ?>">
        <a class="size-21 pl15 pr15 gray-light-2" data-id="fb"><i class="icon-facebook"></i></a>
        <a class="size-21 pl15 pr15 gray-light-2" data-id="vk"><i class="icon-vkontakte"></i></a>
        <a class="size-21 pl15 pr15 gray-light-2" data-id="tw"><i class="icon-twitter"></i></a>
      </div>
    </div>

    <?php if ($data['recommend']) { ?>
      <div class="white-box post-view sticky recommend pt5 pr15 pb5 pl15">
        <h3 class="recommend size-13"><?= lang('Recommended'); ?></h3>
        <?php foreach ($data['recommend'] as  $rec_post) { ?>
          <div class="mb15 hidden flex">
            <a class="gray size-15" href="<?= post_url($rec_post); ?>">
              <?php if ($rec_post['post_answers_count'] > 0) { ?>
                <div class="up-box-post bg-green-400 size-13 center mr15">
                  <?= $rec_post['post_answers_count'] ?>
                </div>
              <?php } else { ?>
                <div class="up-box-post bg-gray-200 gray size-13 center mr15">0</div>
              <?php } ?>
            </a>
            <a class="gray size-13" href="<?= post_url($rec_post); ?>">
              <?= $rec_post['post_title']; ?>
            </a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </aside>
</div>
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
      layer.confirm('<?= lang('Does this violate site rules'); ?>?', {
        icon: 5,
        title: '<?= lang('Report'); ?>',
        btn: ['<?= lang('Yes'); ?>', '<?= lang('No'); ?>']
      }, function(index) {
        $.post('/flag/repost', {
          type,
          post_id,
          content_id
        }, function(str) {
          if (str == 1) {
            layer.msg('<?= lang('Flag not included'); ?>!');
            return false;
          }
          layer.msg('<?= lang('Thanks'); ?>!');
        });
      });
    });
  });
</script>