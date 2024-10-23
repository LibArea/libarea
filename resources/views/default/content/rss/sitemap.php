<?= '<?xml version="1.0" encoding="UTF-8" ?>'; ?><?php $url = config('meta', 'url'); ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc><?= $url; ?></loc>
    <priority>1.0</priority>
    <changefreq>daily</changefreq>
  </url>
  <!-- Sitemap -->
  <?php foreach ($data['topics'] as $topic) : ?>
    <url>
      <loc><?= $url; ?>/topic/<?= $topic['facet_slug']; ?></loc>
      <priority>0.5</priority>
      <changefreq>daily</changefreq>
    </url>
  <?php endforeach; ?>
  <?php foreach ($data['posts'] as $post) : ?>
    <url>
      <loc><?= $url; ?><?= post_slug($post['post_id'], $post['post_slug']); ?></loc>
      <priority>0.5</priority>
      <changefreq>daily</changefreq>
    </url>
  <?php endforeach; ?>
  <url>
    <loc><?= $url; ?>/info</loc>
    <priority>0.5</priority>
    <changefreq>daily</changefreq>
  </url>
</urlset>