<?php
add_action('init', 'uvasomcustomRSS');
function uvasomcustomRSS(){
        add_feed('uvasomcmecourses', 'uvasomcmecustomfeed');
}
function uvasomcmecustomfeed(){

     header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
	 $numposts = 0;
	 
	 $posts = query_posts(array('post_type'=>'cmecourse','orderby'=>get_post_meta( get_the_ID(),'uvacme_date'),'order'=>'ASC')); 
     $more = 1;
    echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

    <rss version="2.0"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:wfw="http://wellformedweb.org/CommentAPI/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:atom="http://www.w3.org/2005/Atom"
        xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
        xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
        <?php do_action('rss2_ns'); ?>
    >

        <channel>
            <title><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
            <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
            <link><?php bloginfo_rss('url') ?></link>
            <description><?php bloginfo_rss("description") ?></description>
            <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
            <language><?php echo get_option('rss_language'); ?></language>
            <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
            <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
            <?php do_action('rss2_head'); ?>
            <?php while( have_posts()) : the_post(); ?>
            <item>
                <title><?php the_title_rss()?></title>
                <link><?php the_permalink_rss() ?></link>
                <comments><?php comments_link_feed(); ?></comments>
                <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
                <dc:creator><?php the_author() ?></dc:creator>
                <?php the_category_rss('rss2') ;
				$startdate = get_post_meta(  get_the_ID(), 'uvacme_date' );?>
                <guid isPermaLink="false"><?php the_guid(); ?></guid>
                <description><![CDATA[<?php $startdate[0]?>]]></description>
                <?php $content = get_the_content_feed('rss2')?>				?>
            <?php if ( strlen( $content  ) > 0 ) : ?>
                <content:encoded><![CDATA[<?php echo '<h4>'.$startdate[0].'</h4>'. $content ?>]]></content:encoded>
            <?php else : ?>
                <content:encoded><![CDATA[<?php $startdate[0]?>]]></content:encoded>
            <?php endif; ?>

                <wfw:commentRss><?php echo esc_url( get_post_comments_feed_link(null, 'rss2') ); ?></wfw:commentRss>
                <slash:comments><?php echo get_comments_number(); ?></slash:comments>
        <?php rss_enclosure(); ?>
            <?php do_action('rss2_item'); ?>
            </item>
            <?php endwhile; ?>
        </channel>
    </rss>
<?php
}
?>