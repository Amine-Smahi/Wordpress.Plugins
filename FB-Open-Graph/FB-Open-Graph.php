<?php
/*
* Plugin Name: FB Open Graph
* Plugin URI: 
* Description: Add facebook open graph meta tags in your post.You can also set default post thumbnail.
* Author: Amine Smahi
* Author URI: https://www.amine-smahi.net
* Version: 1.0.0

* @package WordPress
* @subpackage DOT_CFI
* @author Amine-Smahi
* License:
  Copyright 2017 "FB Open Graph" (mohammed.amine.smahi@gmail.com).

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 1, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY.
*/

if(!defined('ABSPATH')) exit;


class wps_main_fb_og_meta{
	
	public function __construct(){
		// Add menu in admin menu
		add_action('admin_menu','wps_add_fb_og_menu');
		
		// Add meta tags in <head> tag
		add_action('wp_head','wps_add_FB_Open_Graph');
	}	
}

function wps_add_FB_Open_Graph(){
	if(is_single()){
		global $post;
		$post_id = $post->ID;
		$title = html_entity_decode(get_the_title($post_id));
		?>
		<meta property="og:title" content="<?php echo $title; ?>" />
		<meta property="og:site_name" content="<?php echo get_bloginfo(); ?>"/>
		<meta property="og:url" content="<?php echo get_permalink(); ?>" />
		<meta property="og:description" content="<?php echo substr(strip_tags(apply_filters('the_content', $post->post_content)),0,150) ?>" />
		<?php 
		if(has_post_thumbnail()){
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
			echo '<meta property="og:image" content="'.$image[0].'" />';
		}
		else{			
			$images = get_option('wps_fb_og_default_thumb');
			echo '<meta property="og:image" content="'.$images.'" />';
		}?>
		<meta property="og:type" content="blog"/>
		<?php
	}
}

function wps_add_fb_og_menu(){
	add_menu_page(
		'Open Graph Meta',
		'Open Graph Meta',
		'administrator',
		'fb-og-meta',
		'wps_add_fb_og_page',
		'dashicons-facebook-alt'
	);
}

add_action('admin_enqueue_scripts','wps_fb_meta_style');

register_setting('wps_fb_og_fields','wps_fb_og_default_thumb');

function wps_fb_meta_style(){
	wp_enqueue_style( 'fb_admin_css', plugin_dir_url( __FILE__ ) . 'css/fb-meta.css', false, '1.0.0' );
}

function wps_add_fb_og_page(){ ?>
	<div class="wrap">
		<div class="title">
			<h1>FB Open Graph Meta Tags</h1>
		</div>
	</div>
	<div class="wrap">
		<form action="options.php" method="post">
			<?php
				settings_fields('wps_fb_og_fields');
				do_settings_sections('wps_fb_og_fields');
			?>
			<input type="text" class="fb-meta-thumb" placeholder="Default thumbnail url" name="wps_fb_og_default_thumb" value="<?php echo get_option('wps_fb_og_default_thumb'); ?>" />
			<?php submit_button(); ?>
		</form>
	</div>
	<div class="wrap">
		<p>Tag will automatic add in single post.</p>
		<ul>
			<li>1. Post title as og:title</li>
			<li>2. Site name as og:site_name</li>
			<li>3. Post url as og:url</li>
			<li>4. Post description as og:description</li>
			<li>5. Post type as og:type</li>
			<li>6. Post thumbnail as og:image</li>
		</ul>
	</div>
<?php }

new wps_main_fb_og_meta; // Initiate class