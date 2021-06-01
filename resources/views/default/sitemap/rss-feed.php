<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:yandex="http://news.yandex.ru"
     xmlns:media="http://search.yahoo.com/mrss/"
     xmlns:turbo="http://turbo.yandex.ru"
     version="2.0">
    <channel>
        <?php foreach ($data['posts'] as $post) { ?>
        <?= $post['post_content']; ?>
        
            <item turbo="true">
                <link><?= $data['url']; ?>/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?><link>
                <turbo:content>
                    <![CDATA[
                        <?= $post['post_content']; ?>
                    ]]>
                </turbo:content>
            </item>
        <?php } ?>
    </channel>
</rss>
 

 