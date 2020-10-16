<?php

class AIVP_Widget extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'widget-recent-video', 'description' => 'Display the recent video in widgets' );
        parent::__construct('widget_recent_video', 'AIVP Widget', $widget_ops);
    }
    
    function form($instance) {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $video = new WP_Query( array(
            'post_type' => 'aivp',
            'posts_per_page' => 1,
            'orderby'   => 'date',
            'order'     => 'DESC',
        ) );
        if( $video->have_posts() ):
            echo $args['before_widget'];

            if (!empty($title)) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            while( $video->have_posts() ) : $video->the_post(); ?>
                <?php if( get_post_meta(get_the_ID(), "platform", true) == 'wistia' ) : ?>

                    <script src="https://fast.wistia.com/embed/medias/<?php echo (get_aivp_option( 'wistia_access_token' ) ? get_post_meta(get_the_ID(), "hashed-id", true) : get_post_meta(get_the_ID(), "video-id", true)) ; ?>.jsonp" async></script>
                    <script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
                    <div class="wistia_responsive_padding" style="padding:55.31% 0 0 0;position:relative;">
                        <div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;">
                            <div class="wistia_embed wistia_async_<?php echo (get_aivp_option( 'wistia_access_token' ) ? get_post_meta(get_the_ID(), "hashed-id", true) : get_post_meta(get_the_ID(), "video-id", true)) ; ?> videoFoam=true" style="height:100%;position:relative;width:100%">
                                &nbsp;
                            </div>
                        </div>
                    </div>

                <?php endif;
                if( get_post_meta(get_the_ID(), "platform", true) == 'vimeo' ) : ?>

                    <iframe src="https://player.vimeo.com/video/<?php echo get_post_meta(get_the_ID(), "video-id", true); ?>?autoplay=1" width="640" height="354" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>

            <?php endif;
            endwhile;

            wp_reset_postdata(); ?>

        <?php echo $args['after_widget'];
        endif;
    }
}
function aivp_register_widgets() {
    register_widget( 'AIVP_Widget' );
}
add_action( 'widgets_init', 'aivp_register_widgets' );