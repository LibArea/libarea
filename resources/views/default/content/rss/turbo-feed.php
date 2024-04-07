<?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">
  <channel>
    <title><?= $topic['facet_title']; ?></title>
    <link><?= config('meta', 'url'); ?>/topic/<?= $topic['facet_slug']; ?></link>
    <description><?= $topic['facet_description']; ?></description>
    <language><?= config('general', 'lang'); ?></language>
    <?php foreach ($data['posts'] as $post) : ?>
      <item turbo="true">
        <link><?= config('meta', 'url'); ?><?= post_slug($post['post_id'], $post['post_slug']); ?></link>
        <pubDate><?= $post['post_date']; ?></pubDate>
        <turbo:content>
          <![CDATA[
             <header>
                <h1><?= $post['post_title']; ?></h1>
             </header>
              <?php if ($post['post_content_img']) { ?>
                  <figure>
                      <?= Img::image($post['post_content_img'], $post['post_title'], 'img-post', 'post', 'cover'); ?>
                  </figure>
              <?php } ?>    
              <?= $post['post_content']; ?>
          ]]>
        </turbo:content>
      </item>
    <?php endforeach; ?>
  </channel>
</rss>