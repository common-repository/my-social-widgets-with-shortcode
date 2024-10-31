<?php
/*
@package My Social Widgets With ShortCode
@author Bala Krishna
@version 1.0

Plugin Name: My Social Widgets With ShortCode
Plugin URI: http://www.bala-krishna.com/wordpress-plugins/social-widgets-with-shortcode/
Description: Add social media widgets in the sidebar via widget or shortcode. Support Facebook, Twitter, Recent Posts
Author: Bala Krishna
Version: 1.0
Author URI: http://www.bala-krishna.com

Copyright (C) 2009-2010 Balkrishna Verma, bala-krishna.com (krishna711@gmail.com)
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$sw_domain = "social-widgets";
$sw_version = '1.0';

register_activation_hook(__FILE__,'sw_activation');

function sw_activation(){
	global $sw_domain;
	if(!get_option('sw_options')){
		$sw_options = get_option('sw_options');
		$sw_options['facebook'] = 1;
		$sw_options['twitter'] = 1;
		$sw_options['recenct_posts'] = 1;
		update_option('sw_options',$sw_options);
	}
}

if(isset($_REQUEST['submit']) and $_REQUEST['submit'] and isset($_REQUEST['sw_options'])) {
		if(isset($_REQUEST['facebook'])) $facebook = "1"; else $facebook = "0";
		if(isset($_REQUEST['twitter'])) $twitter = "1"; else $twitter = "0";
		if(isset($_REQUEST['recenct_posts'])) $recenct_posts = "1"; else $recenct_posts = "0";

		$sw_options['facebook'] = $facebook;
		$sw_options['twitter'] = $twitter;
		$sw_options['recenct_posts'] = $recenct_posts;
		update_option('sw_options',$sw_options);
}


add_action('admin_menu', 'sw_admin_menu');


function sw_admin_menu() {
  global $sw_domain;
  add_options_page(__('My Social Widgets', $sw_domain), __('My Social Widgets', $sw_domain), 'manage_options', 'sw', 'sw_admin_options');
  //add_options_page('CSMT Settings', 'CSMT Settings', 'manage_options', 'csmt', 'csmt_admin_options');

}

function sw_admin_options() {
  global $sw_domain, $sidebars_widgets;

  if (!current_user_can('manage_options'))  {
    wp_die( _e('You do not have sufficient permissions to access this page.', $sw_domain) );
  }

  echo '<div class="wrap">';
  echo '<h2>' . __("My Social Widgets Settings", $sw_domain) . '</h2>  by
<strong>Bala Krishna (<a href="http://www.bala-krishna.com" target="_blank">http://www.bala-krishna.com</a>)</strong>';
  if(isset($_REQUEST['submit']) and $_REQUEST['submit']) {
  echo '<div class="updated fade" id="message">';
  echo '<p>' . _e("Social Widgets Settings Updated", $sw_domain) . '</p>';
  echo '</div>';
  }
  echo '<div id="poststuff">';
  echo '<div id="postdiv" class="postarea">';

$sw_options = get_option('sw_options');
?>
<table cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td valign="top">
 
<form name="swform" id="swform" action="" method="post">
<input type="checkbox" name="facebook" value="1" id="facebook" <?php if($sw_options['facebook']=='1') print " checked='checked'"; ?> />
<label for="facebook"> <?php echo _e("Enable Facebook Widget", $sw_domain); ?></label><br />
<br />
<input type="checkbox" name="twitter" value="1" id="twitter" <?php if($sw_options['twitter']=='1') print " checked='checked'"; ?> />
<label for="twitter"> <?php echo _e("Enable Twitter Widget", $sw_domain); ?></label><br />
<br />
<input type="checkbox" name="recenct_posts" value="1" id="recenct_posts" <?php if($sw_options['recenct_posts']=='1') print " checked='checked'"; ?> />
<label for="recenct_posts"> <?php echo _e("Enable Recenct Posts Widget", $sw_domain); ?></label><br />
<br />
<input type="hidden" id="sw_options" name="sw_options" value="1" />
<input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID ?>" />

<span id="autosave"></span>
<input class="button-primary" type="submit" name="submit" value="<?php echo 'Save Options'; ?>" style="font-weight: bold;" />
</form>
<br  /><br />
<h2>Short Code - Available Active Widgets</h2> 
<?php

$wid['sw-recent-posts']='SW_Recent_Posts';
$wid['sw-facebook']='SW_Facebook';
$wid['sw-twitter']='SW_Twitter';

foreach($sidebars_widgets as $sidebar => $sidebar_widget){
      foreach($sidebar_widget as $widget){
		$w = array_reverse(explode('-',$widget));
		$widget_id = $w[0];
		$widget_name = str_replace("-".$widget_id,"",$widget);
		//echo $widget_name.":".$widget_id."<br />";
		if (array_key_exists($widget_name, $wid)) {
			$a = get_option('widget_'.$widget_name);
			$a_str = http_build_query($a[$widget_id], '', '&amp;');
			//echo $a_str."<br />";
			echo '<h3>'.$a[$widget_id]['title'].'</h3>';
			?>
			<textarea style="width:500px; height:75px;">[mysocialwidget widget_name="<?php echo $wid[$widget_name];?>" instance="<?php echo $a_str; ?>"]</textarea>
			<?
		}
      }
}

?>


</td>
<td valign="top">
<div style="margin:10px; width:300px; text-align:left;float:left;background-color:white;padding: 10px 10px 10px 10px;margin-right:15px;border: 1px solid #ddd;">
<?php echo _e("Join our mailing list for web development tips, tricks, and tech updates. Sign up today.", $sw_domain); ?><br /> <br />
<!-- Begin MailChimp Signup Form --> 
<!--[if IE]>
<style type="text/css" media="screen">
	#mc_embed_signup fieldset {position: relative;}
	#mc_embed_signup legend {position: absolute; top: -1em; left: .2em;}
</style>
<![endif]--> 
 
<!--[if IE 7]>
<style type="text/css" media="screen">
	.mc-field-group {overflow:visible;}
</style>
<![endif]-->
<script type="text/javascript" src="http://bala-krishna.us1.list-manage.com/js/jquery-1.2.6.min.js"></script> 
<script type="text/javascript" src="http://bala-krishna.us1.list-manage.com/js/jquery.validate.js"></script> 
<script type="text/javascript" src="http://bala-krishna.us1.list-manage.com/js/jquery.form.js"></script> 
<script type="text/javascript" src="http://bala-krishna.us1.list-manage.com/subscribe/xs-js?u=65e3dfd2a866328925b5ab75b&amp;id=a872e5da54"></script> 
 
<div id="mc_embed_signup"> 
<form action="http://bala-krishna.us1.list-manage.com/subscribe/post?u=65e3dfd2a866328925b5ab75b&amp;id=a872e5da54" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" style="font: normal 100% Arial, sans-serif;font-size: 10px;"> 
	
<div class="indicate-required" style="text-align: left;font-style: italic;overflow: hidden;color: #000;margin: 0 0% 0 0;"><?php echo _e("* indicates required", $sw_domain); ?></div> 
<div class="mc-field-group" style="margin: 1.3em 5%;clear: both;overflow: hidden;"> 
<label for="mce-FNAME" style="display: block;margin: .3em 0;line-height: 1em;font-weight: bold;"><?php echo _e("Name ", $sw_domain); ?></label> 
<input type="text" value="" name="FNAME" size="41" class="" id="mce-FNAME" style="margin-right: 0em;padding: .2em .3em;float: left;z-index: 999;"> 
</div> 
<div class="mc-field-group" style="margin: 1.3em 5%;clear: both;overflow: hidden;"> 
<label for="mce-EMAIL" style="display: block;margin: .3em 0;line-height: 1em;font-weight: bold;"><?php echo _e("Email Address ", $sw_domain); ?><strong class="note-required">*</strong> 
</label> 
<input type="text" value="" name="EMAIL" size="41" class="required email" id="mce-EMAIL" style="margin-right: 1.5em;padding: .2em .3em;float: left;z-index: 999;"> 
</div> 
 
 
		<div id="mce-responses" style="float: left;top: -1.4em;padding: 0em .5em 0em .5em;overflow: hidden;width: 90%;margin: 0 5%;clear: both;"> 
			<div class="response" id="mce-error-response" style="display: none;margin: 1em 0;padding: 1em .5em .5em 0;font-weight: bold;float: left;top: -1.5em;z-index: 1;width: 80%;background: #FBE3E4;color: #D12F19;"></div> 
			<div class="response" id="mce-success-response" style="display: none;margin: 1em 0;padding: 1em .5em .5em 0;font-weight: bold;float: left;top: -1.5em;z-index: 1;width: 80%;background: #E3FBE4;color: #529214;"></div> 
		</div> 
		<div><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn" style="clear: both;width: auto;display: block;margin: 1em 0 1em 5%;"></div> 
      <a href="#" id="mc_embed_close" class="mc_embed_close" style="display: none;"><?php echo _e("Close", $sw_domain); ?></a> 
</form> 
</div> 
<!--End mc_embed_signup-->
<?php echo _e("If you like this plugin and find it useful, help keep this plugin free and actively developed by clicking the donate button.", $sw_domain); ?><br />
<a href="http://www.bala-krishna.com" target="_blank"><?php echo _e("Author Home Page", $sw_domain); ?></a> | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=krishna711%40gmail%2ecom&item_name=WP Plugin Support Donation&item_number=Support%20Forum&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank"><?php echo _e("Donate", $sw_domain); ?></a> | <a href="http://www.bala-krishna.com/contact-bala-krishna/" target="_blank"><?php echo _e("Contact Author", $sw_domain); ?></a> | <a href="http://twitter.com/balakrishna" target="_blank"><?php echo _e("Follow me on Twitter", $sw_domain); ?></a> | <a href="http://www.bala-krishna.com/wordpress-plugins/" target="_blank"><?php echo _e("Other Wordpress Plugins by author", $sw_domain); ?></a>
</div>
</td>
</tr>
</tbody>
</table>


<?php 
  echo '</div>';
  echo '</div>';
  echo '</div>';
}

function mysocialwidget($atts) {
    
    global $wp_widget_factory;
    global $wp_registered_widgets, $wp_registered_sidebars, $sidebars_widgets;
    
    extract(shortcode_atts(array(
        'widget_name' => FALSE,
        'instance' => ''
    ), $atts));
    
    $widget_name = wp_specialchars($widget_name);
	//$instance = str_ireplace("&amp;", '&' ,$instance);
    
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            //return '<p>'.sprintf(__("%s: WCNF Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
            return '<p>'.sprintf(__("WCNF -Widget not found."),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;

	// create instance variable array
	$iiii = esc_attr($instance);
	$iii = explode("&amp;", $iiii);
	$instance_arr = array();
	foreach ($iii as $ii) {
		$i = explode("=", $ii);
		$instance_arr[$i[0]]=urldecode($i[1]);
	}


    ob_start();
    the_widget($widget_name, $instance_arr, array('widget_id'=>'arbitrary-instance-'.$id,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
	//echo "<pre>";
    //var_dump($instance_arr);

	//echo "</pre>";
 	//echo '<br /><br />';
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}

function limit_words($string, $limit, $break=".", $pad=" ...") { 
    // return with no change if string is shorter than $limit  
    if(strlen($string) <= $limit) return $string; 
    // is $break present between $limit and the end of the string?  
    if(false !== ($breakpoint = strpos($string, $break, $limit))) { 
        if($breakpoint < strlen($string) - 1) { 
            $string = substr($string, 0, $breakpoint) . $pad; 
        } 
    } return $string; 
}

function link_files() {
	echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>';	
	echo '<script type="text/javascript" src="'.WP_CONTENT_URL.'/plugins/my-social-widgets/jTweetsAnywhere/jquery.jtweetsanywhere-1.3.1.min.js"></script>';	
	echo '<script type="text/javascript" src="http://platform.twitter.com/anywhere.js?id=h55NhogE3MuMVJWRUQA&v=1"></script>';
	echo '<link rel="stylesheet" type="text/css" href="'.WP_CONTENT_URL.'/plugins/my-social-widgets/jTweetsAnywhere/jquery.jtweetsanywhere-1.3.1.css" />';	
	echo '<style type="text/css">';
	echo ' #swfbwidget { border:0px solid #d8dfea; } ';
	echo '</style>';
}

include('widgets/recent_posts.php');
include('widgets/facebook.php');
include('widgets/twitter.php');

$sw_options = get_option('sw_options');
if($sw_options['recenct_posts']=='1') add_action('widgets_init', create_function('', 'return register_widget("SW_Recent_Posts");'));
if($sw_options['facebook']=='1') add_action('widgets_init', create_function('', 'return register_widget("SW_Facebook");'));
if($sw_options['twitter']=='1') add_action('widgets_init', create_function('', 'return register_widget("SW_Twitter");'));

add_action ('wp_head','link_files',100); 
add_shortcode('mysocialwidget','mysocialwidget');

?>