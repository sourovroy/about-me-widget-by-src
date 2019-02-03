<?php

/**
 * Custom Media Function
 */
require_once __DIR__ . '/custom-media.php';

/**
 * Generate Widegt
 */
class About_me_widget extends WP_Widget {

	/**
     * Widget Construct
     */
	public function __construct() {
		$widg_ops = array('description' => __('Name, Designation, Image, Description.', 'aboutwidget'), 'classname' => 'the_about_me_widget');
		$widg_cont = array('width' => 380);
		parent::__construct('About_me_widget', __('About Me', 'aboutwidget'), $widg_ops, $widg_cont );
	}

	/**
     * Widget
     */
	public function widget( $args, $instance ) {

		$title = empty($instance['title']) ? '' : $instance['title'];
		$designation = empty($instance['designation']) ? '' : $instance['designation'];
		$content = empty($instance['content']) ? '' : $instance['content'];
		$autop = empty($instance['autop']) ? '' : $instance['autop'];
		$image = empty($instance['image']) ? '' : $instance['image'];

        //Content Do Shortcode
        $content = do_shortcode($content);

        $widgcont = '';

        if(!empty($title)){
            $widgcont .= $args['before_title'];
            $widgcont .= $title;
            $widgcont .= $args['after_title'];
        }

        if(!empty($designation)){
            $widgcont .= $args['before_title'];
            $widgcont .= $designation;
            $widgcont .= $args['after_title'];
        }

		$widgcont .= ($image) ? '<div class="widget_image"><img src="'.$image.'" alt="'.$title.'"></div>' : '';
		$widgcont .= ($autop == 1) ? wpautop($content) : $content;

		echo $args['before_widget'].$widgcont.$args['after_widget'];
	}


	/**
     * Form
     */
	public function form( $instance ) {

		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$designation = isset( $instance['designation'] ) ? $instance['designation'] : '';
		$content = isset( $instance['content'] ) ? $instance['content'] : '';
		$autop = isset( $instance['autop'] ) ? $instance['autop'] : '';
		$image = isset( $instance['image'] ) ? $instance['image'] : '';

		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Name:', 'aboutwidget'); ?></label>
			<input type="text" value="<?php echo $title; ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" class="widefat">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('designation'); ?>"><?php _e('Designation:', 'aboutwidget'); ?></label>
			<input type="text" value="<?php echo $designation; ?>" name="<?php echo $this->get_field_name('designation'); ?>" id="<?php echo $this->get_field_id('designation'); ?>" class="widefat">
		</p>

        <?php
            $imgarg = array(
                'parent_div_class'=> 'custom-image-upload',
                'field_name' => $this->get_field_name('image'),
                'field_id' => 'upload_logo',
                'field_class' => 'upload_image_field',
                'upload_button_id' => 'upload_logo_button',
                'upload_button_class' => 'upload_logo_button',
                'upload_button_text' => 'Upload',
                'remove_button_id' => 'remove_logo',
                'remove_button_class' => 'remove_logo_button',
                'remove_button_text' => 'Remove'
            );
           src_add_media_image( $imgarg, false, $image );
        ?>

		<p>
			<label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Description:', 'aboutwidget'); ?></label>
			<textarea name="<?php echo $this->get_field_name('content'); ?>" id="<?php echo $this->get_field_id('content'); ?>" cols="20" rows="8" class="widefat" style=""><?php echo $content; ?></textarea>
		</p>

		<p>
			<input <?php checked($autop, 1); ?> value="1" type="checkbox" name="<?php echo $this->get_field_name('autop'); ?>" id="<?php echo $this->get_field_id('autop'); ?>">&nbsp;<label for="<?php echo $this->get_field_id('autop'); ?>"><?php _e('Automatically add paragraphs', 'aboutwidget'); ?></label>
		</p>

		<?php

	}


	/**
     * Update
     */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
		$instance['designation'] = ( ! empty( $new_instance['designation'] ) ) ? $new_instance['designation'] : '';
		$instance['content'] = ( ! empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';
		$instance['autop'] = ( ! empty( $new_instance['autop'] ) ) ? $new_instance['autop'] : '';
		$instance['image'] = ( ! empty( $new_instance['image'] ) ) ? $new_instance['image'] : '';

		return $instance;
	}

}

/**
 * Active Widget
 */
add_action( 'widgets_init', function() {
	register_widget( 'About_me_widget' );
} );
