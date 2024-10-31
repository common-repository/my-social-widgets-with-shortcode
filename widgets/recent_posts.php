<?php
/**
 * @package My Social Widgets With ShortCode - Recent Post Widget Class
 * @author Bala Krishna
 * @version 1.0
*/
class SW_Recent_Posts extends WP_Widget {

	function SW_Recent_Posts() {
		$widget_ops = array('classname' => 'sw_recent_entries', 'description' => __( "Display most recent posts on sidebar") );
		$this->WP_Widget('sw-recent-posts', __('SW - Recent Posts'), $widget_ops);
		$this->alt_option_name = 'sw_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('sw_recent_entries', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;

		$cat = (isset($instance['ecat']) && $instance['ecat']!="") ? $instance['ecat'] : '';
		$args = array(
					  'showposts' => $number,
					  'nopaging' => 0, 
					  'post_status' => 'publish', 
					  'caller_get_posts' => 1,
					  'cat' => $cat
					  );
		$r = new WP_Query($args);
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<div>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
        <?php
			$title = esc_attr(get_the_title());
			if($instance['titlelimit']!=0) {
				$title = limit_words($title, $instance['titlelimit'], " ",""); 
			}
		?>
		<li><a style="color: #555553; font-weight: bold; font-size: 14px" href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php echo $title; ?></a>
		<?
        if($instance['showdescription']=='true') {
			$content = get_the_excerpt();
			if($instance['showmorelink']=='true') {
				$summary = limit_words($content, $instance['excerptlimit'], " ","");  
			} else {
				$summary = limit_words($content, $instance['excerptlimit'], " ");  
			}
			if($instance['showmorelink']=='true') {
				$m = '<a href="'.get_permalink().'">'.$instance['morelinktext'].'</a>';
				echo '<p style="padding: 5px 0 15px">'.$summary." ".$m.'</p>';
			} else {
				echo '<p style="padding: 5px 0 15px">'.$summary.'</p>';
			}
		}
		?>
        </li>
		<?php endwhile; ?>
        </ul>
		</div>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('sw_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['ecat'] = strip_tags($new_instance['ecat']);
		//$instance['icat'] = strip_tags($new_instance['icat']);
		$instance['morelinktext'] = strip_tags($new_instance['morelinktext']);

		$instance['number'] = (int) $new_instance['number'];
		$instance['titlelimit'] = (int) $new_instance['titlelimit'];
		$instance['excerptlimit'] = (int) $new_instance['excerptlimit'];
	
        $instance['showdescription'] = (isset($new_instance['showdescription']) && $new_instance['showdescription']=="true") ? 'true' : 'false';
        $instance['showmorelink'] = (isset($new_instance['showmorelink']) && $new_instance['showmorelink']=="true") ? 'true' : 'false';

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['sw_recent_entries']) )
			delete_option('sw_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('sw_recent_posts', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent Posts';
		$ecat = isset($instance['ecat']) ? esc_attr($instance['ecat']) : '';
		//$icat = isset($instance['icat']) ? esc_attr($instance['icat']) : '';
		$morelinktext = isset($instance['morelinktext']) ? esc_attr($instance['morelinktext']) : 'read more..';

		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;
		if ( !isset($instance['titlelimit']) || !$titlelimit = (int) $instance['titlelimit'] )
			$titlelimit = 0;
		if ( !isset($instance['excerptlimit']) || !$excerptlimit = (int) $instance['excerptlimit'] )
			$excerptlimit = 120;

        $showdescription = (isset($instance['showdescription']) && $instance['showdescription']=="true") ? 'true' : 'false';
        $showmorelink = (isset($instance['showmorelink']) && $instance['showmorelink']=="true") ? 'true' : 'false';


?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label style="width:120px; display:inline-block;" for="<?php echo $this->get_field_id('number'); ?>"><?php _e('No of Posts:'); ?></label>
		<input style="display:inline-block;" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="10" />
        <br />
        <label style="width:120px; display:inline-block;" for="<?php echo $this->get_field_id('ecat'); ?>"><?php _e('Include/Exclude categories:'); ?></label>
		<input style="display:inline-block;" id="<?php echo $this->get_field_id('ecat'); ?>" name="<?php echo $this->get_field_name('ecat'); ?>" type="text" value="<?php echo $ecat; ?>" size="10" /><br /><small style="color:#F00;">ex: 1,2,-4,6 (use - to exclude category)</small>
        <br />
        <label style="width:120px; display:inline-block;" for="<?php echo $this->get_field_id('morelinktext'); ?>"><?php _e('More link text:'); ?></label>
		<input style="display:inline-block;" id="<?php echo $this->get_field_id('morelinktext'); ?>" name="<?php echo $this->get_field_name('morelinktext'); ?>" type="text" value="<?php echo $morelinktext; ?>" size="10" />
        <br />
        <label style="width:120px; display:inline-block;" for="<?php echo $this->get_field_id('excerptlimit'); ?>"><?php _e('Excerpt word limit:'); ?></label>
		<input style="display:inline-block;" id="<?php echo $this->get_field_id('excerptlimit'); ?>" name="<?php echo $this->get_field_name('excerptlimit'); ?>" type="text" value="<?php echo $excerptlimit; ?>" size="10" />
        <br />
        <label style="width:120px; display:inline-block;" for="<?php echo $this->get_field_id('titlelimit'); ?>"><?php _e('Title word limit:'); ?></label>
		<input style="display:inline-block;" id="<?php echo $this->get_field_id('titlelimit'); ?>" name="<?php echo $this->get_field_name('titlelimit'); ?>" type="text" value="<?php echo $titlelimit; ?>" size="10" /><br /><small style="color:#F00;">0 mean no limit</small>
        <br />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('showdescription'); ?>">
            <input id="<?php echo $this->get_field_id('showdescription'); ?>" name="<?php echo $this->get_field_name('showdescription'); ?>" type="checkbox" value="true"   <?php echo (isset($showdescription) && $showdescription=='true') ? 'checked="checked"' : ''; ?>
/> <?php _e('Show Description', 'sw_domain'); ?>
        </label><br />             
        <label for="<?php echo $this->get_field_id('showmorelink'); ?>">
            <input id="<?php echo $this->get_field_id('showmorelink'); ?>" name="<?php echo $this->get_field_name('showmorelink'); ?>" type="checkbox" value="true"   <?php echo (isset($showmorelink) && $showmorelink=='true') ? 'checked="checked"' : ''; ?>
/> <?php _e('Show Read More Link', 'sw_domain'); ?>
        </label><br />             

</p>
<?php
	}
}


?>