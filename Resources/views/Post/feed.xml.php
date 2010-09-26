<?php echo '<?xml version="1.0"?>',"\n" ?>
<rss version="2.0">
  <channel>
    <title><?php echo $config['title'] ?></title>
    <link><?php echo $view['router']->generate('balibali_blog_frontend_index', array(), true) ?></link>
    <description><?php echo $config['description'] ?></description>
<?php foreach ($posts as $post): ?>
    <item>
        <title><?php echo $post->getTitle() ?></title>
        <link><?php echo $view['router']->generate('balibali_blog_frontend_show',
                array('year'  => $post->getPublishedAt()->format('Y'),
                      'month' => $post->getPublishedAt()->format('m'),
                      'slug'  => $post->getSlug()), true) ?></link>
        <pubDate><?php echo $post->getPublishedAt()->format('r') ?></pubDate>
        <description><?php echo $post->getBody() ?></description>
    </item>
<?php endforeach ?>
  </channel>
</rss>
