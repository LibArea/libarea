<?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">
  <channel>
    <title><?= $topic['facet_title']; ?></title>
    <link><?= $data['url']; ?>/topic/<?= $topic['facet_slug']; ?></link>
    <description><?= $topic['facet_description']; ?></description>
    <language>ru</language>
    <?php foreach ($data['posts'] as $post) { ?>
      <item turbo="true">
        <link><?= $data['url']; ?><?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?></link>
        <pubDate><?= $post['post_date']; ?></pubDate>
        <turbo:content>
          <![CDATA[
             <header>
                <h1><?= $post['post_title']; ?></h1>
             </header>
              <?php if ($post['post_content_img']) { ?>
                  <figure>
                      <?= post_img($post['post_content_img'], $post['post_title'], 'img-post', 'cover'); ?>
                  </figure>
              <?php } ?>    
              <?= $post['post_content']; ?>
          ]]>
        </turbo:content>
      </item>
    <?php } ?>
  </channel>
</rss>