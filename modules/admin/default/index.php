<div class="wrap">
  <main class="admin">
    <div class="pt5 pb5 flex">
      <div class="box-number mr10">
        <div class="bg-yellow-500 p10">
          <a class="white" rel="noreferrer gray" href="/admin/posts">
            <div class="size-13 right">
              <i class="icon-book-open"></i>
            </div>
            <div class="count"><?= $data['posts_count']; ?></div>
            <div class="size-15"><?= lang('Posts'); ?></div>
          </a>
        </div>
      </div>
      <div class="box-number">
        <div class="bg-indigo-300 p10 mr10">
          <a class="white" rel="noreferrer gray" href="/admin/answers">
            <div class="size-13 right">
              <i class="icon-comment-empty"></i>
            </div>
            <div class="count"><?= $data['answers_count']; ?></div>
            <div class="size-15"><?= lang('Answers-n'); ?></div>
          </a>
        </div>
      </div>
      <div class="box-number">
        <div class="bg-green-500 p10 mr10">
          <a class="white" rel="noreferrer" href="/admin/users">
            <div class="size-13 right">
              <i class="icon-user-o icon"></i>
            </div>
            <div class="count"><?= $data['users_count']; ?></div>
            <div class="size-15"><?= lang('Users'); ?></div>
          </a>
        </div>
      </div>
      <div class="box-number">
        <div class="bg-blue-400 p10">
          <a class="white" rel="noreferrer" href="/admin/comments">
            <div class="size-13 right">
              <i class="icon-commenting-o"></i>
            </div>
            <div class="count"><?= $data['comments_count']; ?></div>
            <div class="size-15"><?= lang('Comments-n'); ?></div>
          </a>
        </div>
      </div>
    </div>
    <div class="white-box mt10 pt5 pr15 pb5 pl15">
      <h4 class="mt5"><?= lang('Users'); ?> <small>(15)</small></h4>
      <?php foreach ($data['last_visit'] as $user) { ?>
        <div class="gray size-15">
          id<?= $user['user_id']; ?> 
          <a href="/u/<?= $user['user_login']; ?>"><?= $user['user_login']; ?></a> 
          <span class="size-13"> — <?= $user['latest_date']; ?></span>
        </div>
      <?php } ?>
    </div>
    
    <div class="white-box mt10 pt5 pr15 pb5 pl15">
      <h4 class="mt5"><?= lang('Useful resources'); ?></h4>
      <i class="icon-record-outline gray-light"></i> <a rel="noreferrer" href="https://agouti.ru">Agouti.ru</a></br>
      <i class="icon-record-outline gray-light"></i> <a rel="noreferrer" href="https://discord.gg/dw47aNx5nU">Discord</a></br>
      </ul>
      <hr>
      <div class="boxline">
        <label for="name">PC:</label>
        <?= php_uname('s'); ?> <?php echo php_uname('r'); ?>
      </div>
      <div class="boxline">
        <label for="name">PHP:</label>
        <?= PHP_VERSION; ?>
      </div>
      <div class="boxline">
        <label for="name"><?= lang('Freely'); ?>:</label>
        <?= $data['bytes']; ?>
      </div>
    </div>
  </main>
</div>