<?php

namespace Amws;

use WP_Widget;

/**
 * Generate Widegt here
 */
class About_Me_Widget extends WP_Widget {

    /**
     * Widget Construct
     */
    public function __construct() {
        $widget_options = [
            'description' => __( 'Name, Designation, Image, Description.', 'aboutwidget' ),
            'classname'   => 'the_about_me_widget',
        ];

        $control_options = [
            'width' => 380
        ];

        parent::__construct(
            false,
            __( 'About Me', 'aboutwidget' ),
            $widget_options,
            $control_options
        );
    }

    /**
     * Widget Output
     */
    public function widget( $args, $instance ) {

        $title       = empty( $instance['title'] ) ? '' : $instance['title'];
        $designation = empty( $instance['designation'] ) ? '' : $instance['designation'];
        $content     = empty( $instance['content'] ) ? '' : $instance['content'];
        $autop       = empty( $instance['autop'] ) ? '' : $instance['autop'];
        $image       = empty( $instance['image'] ) ? '' : $instance['image'];
        $link_text   = isset( $instance['link_text'] ) ? $instance['link_text'] : '';
        $link_url    = isset( $instance['link_url'] ) ? $instance['link_url'] : '';

        //Content Do Shortcode
        $content = do_shortcode( $content );

        $widgcont = '';

        if ( ! empty( $title ) ) {
            $widgcont .= $args['before_title'];
            $widgcont .= $title;
            $widgcont .= $args['after_title'];
        }

        if ( ! empty( $designation ) ) {
            $widgcont .= $args['before_title'];
            $widgcont .= '<span class="amws-widget-designation">' . $designation . '</span>';
            $widgcont .= $args['after_title'];
        }

        if ( ! empty( $image ) ) {
            $widgcont .= '<div class="amws-widget-image">';

            if ( empty( $link_url ) ) {
                $widgcont .= '<img src="' . $image . '" alt="' . $title . '">';
            } else {
                $widgcont .= '<a href="'. $link_url .'"><img src="' . $image . '" alt="' . $title . '"></a>';
            }

            $widgcont .= '</div>';
        }

        if ( $autop == 1 ) {
            $widgcont .= '<div class="amws-widget-description">' . wpautop( $content ) . '</div>';
        } else {
            $widgcont .= '<div class="wp-caption-text amws-widget-description">' . $content . '</div>';
        }

        if ( ! empty( $link_text ) ) {
            $widgcont .= '<div class="amws-widget-link"><a href="'. $link_url .'" class="url">'. $link_text .'</a></div>';
        }

        echo $args['before_widget'] . $widgcont . $args['after_widget'];
    }


    /**
     * Widget form in wp-admin
     */
    public function form( $instance ) {
        $title       = isset( $instance['title'] ) ? $instance['title'] : '';
        $designation = isset( $instance['designation'] ) ? $instance['designation'] : '';
        $content     = isset( $instance['content'] ) ? $instance['content'] : '';
        $autop       = isset( $instance['autop'] ) ? $instance['autop'] : '';
        $image       = isset( $instance['image'] ) ? $instance['image'] : '';
        $link_text   = isset( $instance['link_text'] ) ? $instance['link_text'] : '';
        $link_url    = isset( $instance['link_url'] ) ? $instance['link_url'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Name:', 'aboutwidget');?></label>
            <input type="text" value="<?php echo $title; ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" class="widefat wgt_src_title_field">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('designation'); ?>"><?php _e('Designation:', 'aboutwidget');?></label>
            <input type="text" value="<?php echo $designation; ?>" name="<?php echo $this->get_field_name('designation'); ?>" id="<?php echo $this->get_field_id('designation'); ?>" class="widefat">
        </p>

        <?php
            // Image upload button
            $this->image_upload_buttons(
                $this->get_field_name('image'),
                $image,
                $this->get_field_id('image')
            );
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Description:', 'aboutwidget');?></label>
            <textarea name="<?php echo $this->get_field_name('content'); ?>" id="<?php echo $this->get_field_id('content'); ?>" cols="20" rows="8" class="widefat" style=""><?php echo $content; ?></textarea>
        </p>

        <p>
            <input <?php checked($autop, 1);?> value="1" type="checkbox" name="<?php echo $this->get_field_name('autop'); ?>" id="<?php echo $this->get_field_id('autop'); ?>">&nbsp;<label for="<?php echo $this->get_field_id('autop'); ?>"><?php _e('Automatically add paragraphs', 'aboutwidget');?></label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Button Text:', 'aboutwidget');?></label>
            <input type="text" value="<?php echo $link_text; ?>" name="<?php echo $this->get_field_name('link_text'); ?>"
                id="<?php echo $this->get_field_id('link_text'); ?>" class="widefat" placeholder="Add a button below description">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('link_url'); ?>"><?php _e('Button & Image Link:', 'aboutwidget');?></label>
            <input type="text" value="<?php echo $link_url; ?>" name="<?php echo $this->get_field_name('link_url'); ?>"
                id="<?php echo $this->get_field_id('link_url'); ?>" class="widefat" placeholder="URL of the button and image">
        </p>

        <?php
    }

    /**
     * Image upload buttons
     */
    private function image_upload_buttons( $field_name, $value = "", $field_id ) {
        ?>
        <div class="custom-image-upload">
            <button type="button" class="button button-primary wgt_src_upload_image">
                <?php echo empty( $value ) ? 'Upload' : 'Change Image'; ?>
            </button>
            <input type="button" class="button button-primary wgt_src_remove_image"
                <?php echo empty( $value ) ? 'disabled="disabled"' : ''; ?> value="Remove">
            <input type="hidden" value="<?php echo empty( $value ) ? '' : $value; ?>" id="<?php echo $field_id; ?>"
                class="wgt_src_uploaded_image_source" name="<?php echo $field_name; ?>">
            <div class="wgt_src_image_priview">
                <?php if( !empty( $value ) ): ?>
                <img src="<?php echo $value; ?>" alt="" style="max-width:150px; height:auto; margin: 10px 0px;">
                <?php endif; ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php
    }

    /**
     * Update
     */
    public function update($new_instance, $old_instance) {
        $instance                = array();
        $instance['title']       = ( ! empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['designation'] = ( ! empty( $new_instance['designation'] ) ) ? $new_instance['designation'] : '';
        $instance['content']     = ( ! empty( $new_instance['content'] ) ) ? $new_instance['content'] : '';
        $instance['autop']       = ( ! empty( $new_instance['autop'] ) ) ? $new_instance['autop'] : '';
        $instance['image']       = ( ! empty( $new_instance['image'] ) ) ? $new_instance['image'] : '';
        $instance['link_text']   = ( ! empty( $new_instance['link_text'] ) ) ? $new_instance['link_text'] : '';
        $instance['link_url']    = ( ! empty( $new_instance['link_url'] ) ) ? esc_url( $new_instance['link_url'] ) : '';

        return $instance;
    }


}
