<?php
/**
 * @package ArchIntelVideoPlayer
 */
/*
Plugin Name: ArchIntel Video Player
Plugin URI: https://github.com/sjAbaquita/aiv-player
Description: Video player for Vimeo and Wistia API
Version: 1.0.0
Author: Seth
Author URI: https://sjabaquita.com
License: GPLv2 or Later
Text Domain: aivp
*/


if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('AIVP') ) :

    class AIVP {

        /** @var string The plugin version number. */
	    var $version = '1.0.0';

        /** @var array The plugin settings array. */
        var $settings = array();
    
        /**
         * __construct
         *
         * A dummy constructor to ensure AIVP is only setup once.
         *
         * @date	23/06/12
         * @since	5.0.0
         *
         * @param	void
         * @return	void
         */	
        function __construct() {
            // Do nothing.
        }

        function initialize() {
            // Define constants.
            $this->define( 'AIVP', true );
            $this->define( 'AIVP_PATH', plugin_dir_path( __FILE__ ) );
            $this->define( 'AIVP_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'AIVP_VERSION', $this->version );
        
            // Define settings.
            $this->settings = array(
                'name'						=> __('AI Video Player', 'aivp'),
                'slug'						=> dirname( AIVP_BASENAME ),
                'version'					=> AIVP_VERSION,
                'basename'					=> AIVP_BASENAME,
                'path'						=> AIVP_PATH,
                'file'						=> __FILE__,
                'url'						=> plugin_dir_url( __FILE__ ),
                'show_admin'				=> true,
                'show_updates'				=> true,
                'stripslashes'				=> false,
                'local'						=> true,
                'json'						=> true,
                'save_json'					=> '',
                'load_json'					=> array(),
                'default_language'			=> '',
                'current_language'			=> '',
                'capability'				=> 'manage_options',
                'uploader'					=> 'wp',
                'autoload'					=> false,
                'select2_version'			=> 4,
                'row_index_offset'			=> 1,
                'remove_wp_meta_box'		=> true
            );

            add_action( 'init', array($this, 'register_post_types'), 5 );

            include_once( AIVP_PATH . 'includes/admin/admin.php');
            include_once( AIVP_PATH . 'includes/custom-meta.php');
            include_once( AIVP_PATH . 'includes/custom-functions.php');
            include_once( AIVP_PATH . 'includes/custom-sidebar-widget.php');
            
        }

        /**
         * register_post_types
         *
         * Registers the ACF post types.
         *
         * @date	22/10/2015
         * @since	5.3.2
         *
         * @param	void
         * @return	void
         */	
        function register_post_types() {
            
            // Register the post type.
            register_post_type('aivp', array(
                'labels'			=> array(
                    'name'					=> __( 'Video', 'aivp' ),
                    'singular_name'			=> __( 'Video', 'aivp' ),
                    'add_new'				=> __( 'Add New' , 'aivp' ),
                    'add_new_item'			=> __( 'Add New Video' , 'aivp' ),
                    'edit_item'				=> __( 'Edit Video' , 'aivp' ),
                    'new_item'				=> __( 'New Video' , 'aivp' ),
                    'view_item'				=> __( 'View Video', 'aivp' ),
                    'search_items'			=> __( 'Search Videos', 'aivp' ),
                    'not_found'				=> __( 'No Videos found', 'aivp' ),
                    'not_found_in_trash'	=> __( 'No Videos found in Trash', 'aivp' ), 
                ),
                'public'			=> true,
                'has_archive'		=> true,
                'show_in_menu'      => false,
                'capability_type'	=> 'post',
                'capabilities'		=> array(
                    'edit_post'			=> 'manage_options',
                    'delete_post'		=> 'manage_options',
                    'edit_posts'		=> 'manage_options',
                    'delete_posts'		=> 'manage_options',
                ),
            ));
        }

        /**
         * has_setting
         *
         * Returns true if a setting exists for this name.
         *
         * @date	2/2/18
         * @since	5.6.5
         *
         * @param	string $name The setting name.
         * @return	boolean
         */
        function has_setting( $name ) {
            return isset($this->settings[ $name ]);
        }
        
        /**
         * get_setting
         *
         * Returns a setting or null if doesn't exist.
         *
         * @date	28/09/13
         * @since	5.0.0
         *
         * @param	string $name The setting name.
         * @return	mixed
         */
        function get_setting( $name ) {
            return isset($this->settings[ $name ]) ? $this->settings[ $name ] : null;
        }
        
        /**
         * update_setting
         *
         * Updates a setting for the given name and value.
         *
         * @date	28/09/13
         * @since	5.0.0
         *
         * @param	string $name The setting name.
         * @param	mixed $value The setting value.
         * @return	true
         */
        function update_setting( $name, $value ) {
            $this->settings[ $name ] = $value;
            return true;
        }

        /**
         * define
         *
         * Defines a constant if doesnt already exist.
         *
         * @date	3/5/17
         * @since	5.5.13
         *
         * @param	string $name The constant name.
         * @param	mixed $value The constant value.
         * @return	void
         */
        function define( $name, $value = true ) {
            if( !defined($name) ) {
                define( $name, $value );
            }
        }

    }

    /*
    * aivp
    *
    * The main function responsible for returning the one true acf Instance to functions everywhere.
    * Use this function like you would a global variable, except without needing to declare the global.
    *
    * Example: <?php $aivp = aivp(); ?>
    *
    * @date	4/09/13
    * @since	4.3.0
    *
    * @param	void
    * @return	AIVP
    */
    function aivp() {
        global $aivp;
        
        // Instantiate only once.
        if( !isset($aivp) ) {
            $aivp = new AIVP();
            $aivp->initialize();
        }
        return $aivp;
    }
    
    // Instantiate.
    aivp();
    
endif; // class_exists check