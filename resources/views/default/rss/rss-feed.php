<?= '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
	<title><?= $topic['facet_title']; ?></title>
	<link><?= $data['url']; ?>/topic/<?= $topic['facet_slug']; ?></link>
	<description>RSS feed for <?= $topic['facet_title']; ?></description>
	<atom:link href="<?= $data['url']; ?>/rss-feed/topic/<?= $topic['facet_slug']; ?>" rel="self" type="application/rss+xml" />
	  <?php foreach ($data['posts'] as $post) { ?>
		<item>
		  <title><?= $post['post_title']; ?></title>
		  <description><![CDATA[{{ <?= $post['post_content']; ?> }}]]></description>
		  <link><?= $data['url']; ?><?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?></link>
		  <pubDate><?= date(DATE_RFC822, strtotime($post['post_date'])); ?></pubDate>
		  <guid><?= $data['url']; ?><?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?></guid>
		</item>
	<?php } ?>
  </channel>
</rss>