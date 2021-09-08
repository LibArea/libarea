<div class="wrap">
  <main class="admin white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/admin', lang('Admin'), '/admin/badges', lang('Badges'), lang('Reward the user') . ' ' . $data['user']['user_login']); ?>

    <div class="box badges">
      <form action="/admin/badge/user/add" method="post">
        <?= csrf_field() ?>
        <div class="boxline">
          <label class="form-label" for="post_content"><?= lang('Badge'); ?></label>
          <select class="form-input" name="badge_id">
            <?php foreach ($data['badges'] as $badge) { ?>
              <option value="<?= $badge['badge_id']; ?>"> <?= $badge['badge_title']; ?></option>
            <?php } ?>
          </select>
          <input type="hidden" name="user_id" id="post_id" value="<?= $data['user']['user_id']; ?>">
        </div>
        <input type="submit" class="button" name="submit" value="<?= lang('Add'); ?>" />
      </form>
    </div>
  </main>
</div>