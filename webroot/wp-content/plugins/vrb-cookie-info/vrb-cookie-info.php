<?php

/**
 * Plugin Name: VRB Cookie Confirm
 * Version: 0.1
 * Description: Cookie confirm info
 * Author: Rafał Bernaczek ak.
 * VRB
 * Author URI: http://www.rafal.bernaczek.pl
 * License: GPL2
 */
class VRB_cookie_confirm extends WP_Widget {

	// constructor
	function VRB_cookie_confirm() {
		parent::__construct(false, $name = __('VRB Cookie Confirm', 'VRB_cookie_confirm'), array (
			'description' => __('Cookie info desc', 'VRB_cookie_confirm')
		));
	}

	// widget form creation
	function form($instance) {
		if ($instance) {
			$buttonTitle = esc_attr($instance['buttonTitle']);
			$message = esc_html($instance['message']);
		}
		else {
			$buttonTitle = '';
			$message = '';
		}
		?>
<p>
	<label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Confirm message:', 'VRB_cookie_confirm'); ?></label>
	<textarea class="widefat"
		id="<?php echo $this->get_field_id('message'); ?>"
		name="<?php echo $this->get_field_name('message'); ?>"><?php echo $message; ?></textarea>
</p>
<p>
	<label for="<?php echo $this->get_field_id('buttonTitle'); ?>"><?php _e('Button title:', 'VRB_cookie_confirm'); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id('buttonTitle'); ?>"
		name="<?php echo $this->get_field_name('buttonTitle'); ?>" type="text"
		value="<?php echo $buttonTitle; ?>" />
</p>
<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['buttonTitle'] = strip_tags($new_instance['buttonTitle']);
		$instance['message'] = strip_shortcodes($new_instance['message']);
		return $instance;
	}

	// widget display
	function widget($args, $instance) {
		extract($args);

		$buttonTitle = $instance['buttonTitle'];
		$message = $instance['message'];
		echo $before_widget;

		echo '<link rel="stylesheet" href="' . plugin_dir_url(__FILE__) . 'css/vrb_cookie_confirm.css" />';
		echo '<script src="' . plugin_dir_url(__FILE__) . 'js/vrb_cookie_confirm.js"></script>';
		echo '<script> cokieConfirm({messageText: "' . esc_js($message) . '", buttonText: "' . $buttonTitle . '"}); </script>';

		echo $after_widget;
		load_textdomain('', '');
	}
}

add_action('widgets_init', create_function('', 'return register_widget("VRB_cookie_confirm");'));

function VRB_cookie_confirm_setup() {
	load_theme_textdomain('VRB_cookie_confirm', plugin_dir_path(__FILE__) . '/languages');
}
add_action('after_setup_theme', 'VRB_cookie_confirm_setup');

