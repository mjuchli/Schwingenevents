<?php 
/*
Plugin Name: Agenda
Plugin URI: http://www.impres-sign.com/
Description: Events-Register (Agenda) mit Download von Rangliste. In unserem Falle wird dies fuer den schweizer Schwingsport gebraucht, um alle Schwingfeste und Events zu erfassen.
Version: 1.0
Author: Marc Juchli
Author URI: http://www.impres-sign.com/
License: GPL
Stable Tag: 1.0
*/
?>

<?php
add_action('init', 'agenda');
function agenda() {
 
        $labels = array(
                'name' => _x('Agenda', 'post type general name'),
                'singular_name' => _x('Agenda', 'post type singular name'),
                'add_new' => _x('Add New', 'portfolio item'),
                'add_new_item' => __('Add New Portfolio Item'),
                'edit_item' => __('Edit Portfolio Item'),
                'new_item' => __('New Portfolio Item'),
                'view_item' => __('View Portfolio Item'),
                'search_items' => __('Search Portfolio'),
                'not_found' =>  __('Nothing found'),
                'not_found_in_trash' => __('Nothing found in Trash'),
                'parent_item_colon' => ''
        );
 
        $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'query_var' => true,
                'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
                'rewrite' => true,
                'rewrite' => array('slug' => 'agenda'),
                'capability_type' => 'post',
                'hierarchical' => false,
                'menu_position' => 4,
                'supports' => array('thumbnail')
          ); 
 
        register_post_type( 'agenda' , $args );
}
?>