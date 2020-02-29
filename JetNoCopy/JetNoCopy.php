<?php
/*
Plugin Name: JetNoCopy
Plugin URI:  http://jetlight-studio.tk
Description: JetNoCopy is a tiny plugin to not allowed right click function. Protected your contents by disabling mouse and keyboard commands.
Version: 1.0
Author: Amine Smahi
Author URI: http://www.amine.smahi.net
Studio: JetLight Studio
License: MIT License
*/
 


add_action('wp_head','jetnocopy');


function jetnocopy() {
   // first of all, i'm ready to disable right click function
	$jetnocopy_out="<script language=JavaScript>
    window.ondragstart = function() { return false; } 
    window.onload = function start() {
        document.body.onselectstart = function() {
          return false;
        }
        document.body.oncontextmenu = function() {
          return false;
        }
      }
    document.onkeydown = function(e) {
        if (e.ctrlKey && 
            (e.keyCode === 85 )) {
            return false;
        }
      }
	</script>";

	echo $jetnocopy_out;
}
