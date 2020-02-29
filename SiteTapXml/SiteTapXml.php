<?php 
/*
Plugin Name: SiteTapXml
Plugin URI: 
Description: SiteTapXml creates an XML for use with Google and Yahoo (and Yes! Bing too)
Just install it to your wordpress installation and let the plugin do his job
Version: 1.0
Author: Amine-Smahi
Author URI: http://amine-smahi.my-style.in
Requires at least: 3.5
Tested up to: 4.8.2
*/


/* Coding start */
function Site_Tap_Xml() {
  $postsForSitemap = get_posts(array(
    'numberposts' => -1,
    'orderby' => 'modified',
    'post_type'  => array('post','page','product'),
    'order'    => 'DESC'
  ));

  $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
  $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

  foreach($postsForSitemap as $post) {
    setup_postdata($post);

    $postdate = explode(" ", $post->post_modified);

    $sitemap .= '<url>'.
      '<loc>'. get_permalink($post->ID) .'</loc>'.
      '<lastmod>'. $postdate[0] .'</lastmod>'.
      '<changefreq>daily</changefreq>'.
      '<priority>0.8</priority>'.
    '</url>';
  }

  $sitemap .= '</urlset>';

  $fp = fopen(ABSPATH . "sitemap.xml", 'w');
  fwrite($fp, $sitemap);
  fclose($fp);
}
add_action("publish_post", "Site_Tap_Xml");
add_action("publish_page", "Site_Tap_Xml");
/* Ok that's all */
?>