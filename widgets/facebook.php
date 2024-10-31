<?php
/**
 * @package My Social Widgets With ShortCode - Facebook Widget Class
 * @author Bala Krishna
 * @version 1.0
*/

class SW_Facebook extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'sw_facebook_entries', 'description' => __('Your Facebook like box', 'sw_domain'));
		parent::__construct('sw-facebook', '&nbsp;' . __('SW - Facebook', 'sw_domain'), $widget_ops);
	}
	
	function widget($args, $instance) {
		extract($args);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Facebook', 'sw_domain') : $instance['title'], $instance, $this->id_base);
		$url = isset($instance['url']) ? $instance['url'] : '';
		
		echo '<div class="' . $widget_width . '">' . 
			$before_widget;
		
		if ($title) { 
			echo $before_title . $title . $after_title;
		}
		
		if($instance['showborder']=='false') $bordercolor = 'ffffff'; else $bordercolor = $instance['bordercolor'];
		$height = $instance['height'];
		
//		echo '<div id="swfbwidget"><iframe src="https://www.facebook.com/plugins/likebox.php?href=' . urlencode($url) . '&amp;width='.$instance['width'].'&amp;height='.$height.'&amp;colorscheme='.$instance['colorscheme'].'&amp;show_faces='.$instance['showfaces'].'&amp;border_color=%23'.$instance['bordercolor'].'&amp;stream='.$instance['showstream'].'&amp;connections=12&amp;force_wall=true&amp;header='.$instance['showheader'].'" scrolling="no" frameborder="0" style="border:none; background:#ffffff; overflow:hidden; width:'.$instance['width'].'px; height:'.$instance['height'].'px;" allowTransparency="true"></iframe></div>' . 
		echo '<div id="swfbwidget"><iframe src="https://www.facebook.com/plugins/likebox.php?href=' . urlencode($url) . '&amp;width='.$instance['width'].'&amp;height='.$height.'&amp;colorscheme='.$instance['colorscheme'].'&amp;show_faces='.$instance['showfaces'].'&amp;border_color=%23'.$bordercolor.'&amp;stream='.$instance['showstream'].'&amp;connections='.$instance['connections'].'&amp;force_wall=true&amp;header='.$instance['showheader'].'" scrolling="no" frameborder="0" style="border:none; background:#ffffff; overflow:hidden; width:'.$instance['width'].'px; height:'.$height.'px;" allowTransparency="true"></iframe></div>' . 
			'<div class="cl"></div>' . 
			$after_widget . 
		'</div>';
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['url'] = strip_tags($new_instance['url']);
        $instance['width'] = strip_tags($new_instance['width']);
        $instance['height'] = strip_tags($new_instance['height']);
        $instance['bordercolor'] = strip_tags($new_instance['bordercolor']);
        $instance['colorscheme'] = strip_tags($new_instance['colorscheme']);
        $instance['connections'] = (isset($new_instance['connections']) && !empty($new_instance['connections'])) ? $new_instance['connections'] : '10';
        $instance['showborder'] = (isset($new_instance['showborder']) && $new_instance['showborder']=="true") ? 'true' : 'false';
        $instance['showheader'] = (isset($new_instance['showheader']) && $new_instance['showheader']=="true") ? 'true' : 'false';
        $instance['showstream'] = (isset($new_instance['showstream']) && $new_instance['showstream']=="true") ? 'true' : 'false';
        $instance['showfaces'] = (isset($new_instance['showfaces']) && $new_instance['showfaces']=="true") ? 'true' : 'false';
	
		return $instance;
	}
	
    function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : 'Facebook';
        $url = isset($instance['url']) ? esc_attr($instance['url']) : '';
        $connections = isset($instance['connections']) ? esc_attr($instance['connections']) : '10';
        $width = isset($instance['width']) ? esc_attr($instance['width']) : '292';
        $height = isset($instance['height']) ? esc_attr($instance['height']) : '228';
        $bordercolor = isset($instance['bordercolor']) ? esc_attr($instance['bordercolor']) : 'd8dfea';
        $colorscheme = isset($instance['colorscheme']) ? esc_attr($instance['colorscheme']) : 'light';
        $showborder = (isset($instance['showborder']) && $instance['showborder']=="true") ? 'true' : 'false';
        $showheader = (isset($instance['showheader']) && $instance['showheader']=="true") ? 'true' : 'false';
        $showstream = (isset($instance['showstream']) && $instance['showstream']=="true") ? 'true' : 'false';
        $showfaces = (isset($instance['showfaces']) && $instance['showfaces']=="true") ? 'true' : 'false';
      ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'sw_domain'); ?>:<br />
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Facebook Page URL', 'sw_domain'); ?> :<br />
                <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
            </label>
        </p>
        <p>
            <label style="width:80px; display:inline-block;" for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Connections', 'sw_domain'); ?>:
            </label>
            <input style="display:inline-block;" id="<?php echo $this->get_field_id('connections'); ?>" name="<?php echo $this->get_field_name('connections'); ?>" type="text" value="<?php echo $connections; ?>" size="6" />
            <br />
            <label style="width:80px; display:inline-block;" for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width', 'sw_domain'); ?>:
            </label>
            <input style="display:inline-block;" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" size="6" />px
            <br />
            <label style="width:80px; display:inline-block;" for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height', 'sw_domain'); ?>:
            </label>
                 <input style="display:inline-block;" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" size="6" />px
                       <br />

               <label style="width:80px; display:inline-block;" for="<?php echo $this->get_field_id('bordercolor'); ?>"><?php _e('Border Color', 'sw_domain'); ?>:
            </label>
                <input style="display:inline-block;" id="<?php echo $this->get_field_id('bordercolor'); ?>" name="<?php echo $this->get_field_name('bordercolor'); ?>" type="text" value="<?php echo $bordercolor; ?>" size="6" /> 
 <br />                
           <label for="<?php echo $this->get_field_id('colorscheme'); ?>"><?php _e('Color Scheme', 'sw_domain'); ?>:
            </label>             
                 <select style="display:inline-block;" id="<?php echo $this->get_field_id('colorscheme'); ?>" name="<?php echo $this->get_field_name('colorscheme'); ?>">
                 <option value="light" <?php echo (isset($colorscheme) && $colorscheme=="light") ? 'selected="selected"' : ''; ?>>Light</option>
                 <option value="dark" <?php echo (isset($colorscheme) && $colorscheme=="dark") ? 'selected="selected"' : ''; ?>>Dark</option>
                </select>
            
            </p>
                <p>
           <label for="<?php echo $this->get_field_id('showborder'); ?>">
                <input id="<?php echo $this->get_field_id('showborder'); ?>" name="<?php echo $this->get_field_name('showborder'); ?>" type="checkbox" value="true" size="3"       <?php echo (isset($showborder) && $showborder=='true') ? 'checked="checked"' : ''; ?>
 /> <?php _e('Show Border', 'sw_domain'); ?>
            </label><br />             
            <label for="<?php echo $this->get_field_id('showheader'); ?>">
                <input id="<?php echo $this->get_field_id('showheader'); ?>" name="<?php echo $this->get_field_name('showheader'); ?>" type="checkbox" value="true" size="3" <?php echo (isset($showheader) && $showheader=='true') ? 'checked="checked"' : ''; ?> /> <?php _e('Show Header', 'sw_domain'); ?>
            </label><br />
            <label for="<?php echo $this->get_field_id('showstream'); ?>">
                <input id="<?php echo $this->get_field_id('showstream'); ?>" name="<?php echo $this->get_field_name('showstream'); ?>" type="checkbox" value="true" size="3" <?php echo (isset($showstream) && $showstream=='true') ? 'checked="checked"' : ''; ?> /> <?php _e('Show Stream', 'sw_domain'); ?>
            </label><br />
           <label for="<?php echo $this->get_field_id('showfaces'); ?>">
                <input id="<?php echo $this->get_field_id('showfaces'); ?>" name="<?php echo $this->get_field_name('showfaces'); ?>" type="checkbox" value="true" size="3" <?php echo (isset($showfaces) && $showfaces=='true') ? 'checked="checked"' : ''; ?> /> <?php _e('Show Faces', 'sw_domain'); ?>
            </label>
                         

        </p>
        <div style="clear:both;"></div>
        <?php
    }
}
?>