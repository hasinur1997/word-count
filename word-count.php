<?php 
/*
Plugin Name: Word Count
Plugin URI: https://word-count.org
Description: A wordpress post word count plugin
Version: 1.0.0
Author: Hasinur Rahman
Author URI: https://www.hasinur.me
License: GPLv2 or later
Text Domain:  word-count
*/

/**
 * Word_Count Class
 */
final class Word_Count {
    /**
     * Initialize
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Instantiate the class
     *
     * @return object
     */
    public static function instance() {
        static $instance = false;

        if ( ! $instance  ) {
            $instance = new Word_Count();
        }

        return $instance;
    }

    /**
     * Initialize all hooks
     *
     * @return void
     */
    public function init_hooks() {
        // Plugin activation hook
        register_activation_hook( __FILE__, [ $this, 'word_count_activation' ] );

        // Plugin deactivation hook
        register_deactivation_hook( __FILE__, [ $this, 'word_count_deactivation' ] );

        add_action( 'plugins_loaded', [ $this, 'word_count_load_text_dommain' ] );

        add_filter( 'the_content', [ $this, 'word_count_content' ] );

        add_filter( 'the_content',  [ $this, 'wordcount_reading_time' ] );
    }

    /**
     * Do someting when activating the plugin
     *
     * @return void
     */
    public function word_count_activation() {
        return __( 'Do something, when active the plugin', 'word-count' );
    }

    /**
     * Do something when deactivate the plugin
     *
     * @return void
     */
    public function word_count_deactivation() {
        return __( 'Do something, when deactivate the plugin', 'word-count' );
    }

    /**
     * Load text domain
     *
     * @return void
     */
    public function word_count_load_text_dommain() {
        load_textdomain( 'word-count', false, dirname(__FILE__) . '/languages' );
    }

    /**
     * Count word for the post content
     *
     * @param string $content
     * 
     * @return string
     */
    public function word_count_content( $content ) {
        $striped_content = strip_tags( $content );
        $wordn           = str_word_count( $striped_content );
        $label           = __( 'Total Number Of Words', 'word-count' );

        $label = apply_filters('wordcount_heading', $label);
        $tag = apply_filters('wordcount_tag', 'p');

        $content .= sprintf('<%s>%s: %s </%s>', $tag, $label, $wordn, $tag );
        
        return $content;
    }

    /**
     * Count reading time of a post
     *
     * @param string $content
     * 
     * @return string
     */
    public function wordcount_reading_time( $content ) {
        $striped_content = strip_tags( $content );
        $wordn = str_word_count( $striped_content );
        $munuites = floor( $wordn / 200 );
        $seconds = floor( $wordn % 200 / (200 / 60) );
    
        $label = __( 'Total Reading Time', 'word-count' );
        $label  = apply_filters( 'wordcount_reading_time_heading', $label );
        $tag = apply_filters( 'wordcount_reading_time_tag', 'p' );
    
        $content .= sprintf('<%s> %s: %s minutes %s seconds </%s>', $tag, $label, $munuites, $seconds, $tag);

        return $content;
    }
}

// Word Count Plugin
Word_Count::instance();



