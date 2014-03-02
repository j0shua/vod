<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"> 
            <?php foreach ($resource_video_data as $v) { ?>
        <url> 
            <loc><?php echo $v['url']; ?></loc>
            <video:video>
                <video:thumbnail_loc><?php echo $v['thumbnail_url']; ?></video:thumbnail_loc> 
                <video:title><?php echo $v['title']; ?></video:title>
                <video:description><?php echo $v['description']; ?></video:description>
                <video:duration><?php echo $v['duration']; ?></video:duration>
                <video:live>no</video:live>
            </video:video> 
        </url> 
    <?php } ?>
</urlset>