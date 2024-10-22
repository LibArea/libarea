<?= '<?xml version="1.0" encoding="utf-8"?>';?>
<?php $url = config('meta', 'url'); ?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?= $topic['facet_title']; ?></title>
    <link><?= $url; ?>/topic/<?= $topic['facet_slug']; ?></link>
    <description>RSS feed for <?= $topic['facet_title']; ?></description>
    <atom:link href="<?= $url; ?>/rss-feed/topic/<?= $topic['facet_slug']; ?>" rel="self" type="application/rss+xml" />
    <?php foreach ($data['posts'] as $post) : ?>
      <item>
        <title><![CDATA[{{ <?= $post['post_title']; ?> }}]]></title>
        <description>
          <![CDATA[{{ <?= strip_tags($post['post_content']); ?> }}]]>
        </description>
        <link><?= $url; ?><?= post_slug($post['post_id'], $post['post_slug']); ?></link>
        <pubDate><?= date(DATE_RFC822, strtotime($post['post_date'])); ?></pubDate>
        <guid><?= $url; ?><?= post_slug($post['post_id'], $post['post_slug']); ?></guid>
      </item>
    <?php endforeach; ?>
  </channel>
</rss>
