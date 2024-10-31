<?php
/**
 * @package My Social Widgets With ShortCode - Twitter Widget Class
 * @author Bala Krishna
 * @version 1.0
*/

class SW_Twitter extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'sw_twitter_items', 'description' => __('Your Twitter account latest tweets', 'sw_domain'));
		parent::__construct('sw-twitter', '&nbsp;' . __('SW - Twitter', 'sw_domain'), $widget_ops);
	}
	
	function widget($args, $instance) {
		extract($args);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Twitter', 'sw_domain') : $instance['title'], $instance, $this->id_base);
		$user = isset($instance['user']) ? $instance['user'] : '';
		$number = isset($instance['number']) ? (int) $instance['number'] : '';
		$twitstyle = isset($instance['twitstyle']) ? $instance['twitstyle'] : 'None';
		
		$uid = uniqid();
		
        if (empty($instance['number']) || !$number = absint($instance['number'])) {
            $number = 3;
        } elseif ($number < 1) {
            $number = 1;
        } elseif ($number > 20) {
            $number = 20;
        }
		
		echo '<div class="' . $widget_width . '">' . 
			$before_widget;
?>
		<style type="text/css">
		<?php if($twitstyle=="Bullets") { ?>
		.jta-tweet-list
		{
			list-style:disc;
			padding:0px!important;
			margin:0px!important;
		}
		.jta-tweet-list-item
		{
			padding: 8px 0px 8px 5px!important;
			position: relative;
			margin-left:15px;
			border-bottom: 0px solid #e4e4e4;
		}
		.jta-tweet-list-item:first-child
		{
		    border-top:0px solid #e4e4e4;
		}
		<?php } else if($twitstyle=="TwitterBird") { ?>
		.jta-tweet-list-item
		{
			padding: 8px 0px 14px 35px!important;
			position: relative;
		}
		
		.jta-tweet-list li:before
		{
		  background: url("<?php echo WP_CONTENT_URL; ?>/plugins/my-social-widgets/images/twitter.png") no-repeat scroll 6px 7px #70BDCB; 
		  border-radius: 100% 100% 100% 100%;
		  content: "";
		  display: block;
		  height: 24px;
		  left: 0;
		  position: absolute;
		  top: 12px;
		  width: 24px;
		}		
		<?php } ?>
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function () { 
				jQuery('#<?php echo $args['widget_id']; ?>_tweets').jTweetsAnywhere( { 
					username : '<?php echo $user; ?>', 
					count : <?php echo $number; ?>, 
					showTweetFeed : { 
						showTwitterBird : false, 
						showGeoLocation : true, 
						showInReplyTo : true, 
						includeRetweets : true, 
						showProfileImages: <?php echo (isset($twitstyle) && $twitstyle=="ProfileImage") ? 'true' : 'false';?>,
						showUserScreenNames: false,
						showUserFullNames: false,
						showActionReply: false,
						showActionRetweet: false,
						showActionFavorite: false						
						///
					} 
				} ); 
			} );
		</script>
<?php 
		if ($title) { 
			echo $before_title . $title . $after_title;
		}
		
		echo '<div id="' . $args['widget_id'] . '_tweets"></div>' . 
			$after_widget . 
		'</div>';
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['user'] = strip_tags($new_instance['user']);
        $instance['number'] = absint($new_instance['number']);
        $instance['twitstyle'] = strip_tags($new_instance['twitstyle']);
		
		return $instance;
	}
	
    function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $user = isset($instance['user']) ? esc_attr($instance['user']) : '';
        $twitstyle = isset($instance['twitstyle']) ? esc_attr($instance['twitstyle']) : '';
        $number = (isset($instance['number']) && $instance['number'] != 0) ? absint($instance['number']) : 3;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'sw_domain'); ?>:<br />
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('Twitter Username', 'sw_domain'); ?>:<br />
                <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo $user; ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e("No of Tweets", 'sw_domain'); ?>:
            </label>
                <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('twitstyle'); ?>"><?php _e("Select list style", 'sw_domain'); ?>:
                </label>
                 <select id="<?php echo $this->get_field_id('twitstyle'); ?>" name="<?php echo $this->get_field_name('twitstyle'); ?>">
                 <option value="None" <?php echo (isset($twitstyle) && $twitstyle=="None") ? 'selected="selected"' : ''; ?>>None</option>
                 <option value="Bullets" <?php echo (isset($twitstyle) && $twitstyle=="Bullets") ? 'selected="selected"' : ''; ?>>Bullets</option>
                 <option value="TwitterBird" <?php echo (isset($twitstyle) && $twitstyle=="TwitterBird") ? 'selected="selected"' : ''; ?>>Twitter Bird</option>
                 <option value="ProfileImage" <?php echo (isset($twitstyle) && $twitstyle=="ProfileImage") ? 'selected="selected"' : ''; ?>>Profile Image</option>
                </select>

        </p>        
        <div style="clear:both;"></div>
        <?php
    }

}
?>