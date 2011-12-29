<?php 
/*
Plugin Name: Schwingen Agenda
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
//Plugin-init
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

//Einteilung von Verwaltungsmaske
add_action("admin_init", "agenda_init");
function agenda_init(){
	add_meta_box("dates_meta", "Datum", "dates_meta", "agenda", "side", "low");
	add_meta_box("credits_meta", "Angaben zum Schwingfest", "credits_meta", "agenda", "normal", "low");
	add_meta_box("upload_meta", "Rangliste", "upload_meta", "agenda", "normal", "low");
}

//Input Felder credits_meta (Angaben zum Schwingfest)
function credits_meta(){
	global $post;
	$custom = get_post_custom($post->ID);
	$description = $custom["description"][0];
	$place = $custom["place"][0];
	?>
	<label>Bezeichnung:</label><br />
	<input size="50" name="description" value="<?php echo $description; ?>" /><br />
	<label>Ortschaft:</label><br />
	<input size="50" name="place" value="<?php echo $place; ?>" />
	<?php
}

//Input Felder upload_meta (Rangliste)
function upload_meta(){
	global $post;
	$custom = get_post_custom($post->ID);
	$link = $custom["link"][0];
	?>
	<label>Link:</label><br />
	<input size="50" name="link" value="<?php echo $link; ?>" /><br />
	<?php
}

//Input Felder dates_meta (Datum)
function dates_meta(){
	global $post;
	$custom = get_post_custom($post->ID);
	$dateFrom = $custom["dateFrom"][0];
	//$dateTo = $custom["dateTo"][0];
	?>
	<label>Beginn:</label><br />
	<input size="25" name="dateFrom" value="<?php echo $dateFrom; ?>" /><br />
	<!-- <label>Ende:</label><br /> 
	<input size="25" name="dateTo" value="<?php //echo $dateTo; ?>" /><br /> -->
	<?php
}

//Zuordung fuer Update
function save_details(){
	global $post;
	global $wpdb;
	update_post_meta($post->ID, "description", $_POST["description"]);
	update_post_meta($post->ID, "place", $_POST["place"]);
	update_post_meta($post->ID, "link", $_POST["link"]);
	update_post_meta($post->ID, "dateFrom", $_POST["dateFrom"]);
	//update_post_meta($post->ID, "dateTo", $_POST["dateTo"]);
}
add_action('save_post', 'save_details');

// Formatierung der Tabelle im backend
add_filter('manage_edit-agenda_columns', 'add_new_agenda_columns');
function add_new_agenda_columns($gallery_columns) {
	$new_columns['cb'] = '<input type="checkbox" />';
	$new_columns['id'] = __('ID');
	$new_columns['dateFrom'] = __('Datum');
	$new_columns['description'] = __('Schwingfest');
	$new_columns['place'] = __('Ortschaft');
	$new_columns['link'] = _x('Rangliste');

	return $new_columns;
}

// Formatierung der Tabelle im backend
add_action('manage_agenda_posts_custom_column', 'manage_agenda_columns', 10, 2);
function manage_agenda_columns($column_name, $id) {
	global $wpdb;
	switch ($column_name) {
		case 'id':
			echo $id;
			break;
		case 'dateFrom':
			echo "<a href=\"post.php?post=".$id."&action=edit\">".get_post_meta($id, 'dateFrom', true)."</a>";
			break;
		case 'description':
			echo "<a href=\"post.php?post=".$id."&action=edit\">".get_post_meta($id, 'description', true)."</a>";
			break;
		case 'place':
			echo "<a href=\"post.php?post=".$id."&action=edit\">".get_post_meta($id, 'place', true)."</a>";
			break;
		case 'link':
			echo "<a href=\"post.php?post=".$id."&action=edit\">".get_post_meta($id, 'link', true)."</a>";
			break;
		default:
			break;
	} // end switch
}

// -- BACKEND nun abgeschlossen --

//Liste im Frontend
function frontend_list() {
	global $post;
	rewind_posts();

	// Create a new WP_Query() object
	$wpcust = new WP_Query(
		array(
		'post_type' => array('agenda'),
		'showposts' => '500', // or 10 etc. however many you want
		'orderby' => 'dateFrom', 'order' => 'ASC'
		)
	);
	//print_r($wpcust);
	if ( $wpcust->have_posts() ) : while( $wpcust->have_posts() ) : $wpcust->the_post();
		$description = get_post_meta($post->ID, 'description', true);
		$place = get_post_meta($post->ID, 'place', true);
		$link = get_post_meta($post->ID, 'link', true);
		$dateFrom = get_post_meta($post->ID, 'dateFrom', true);
			?>
			<ul id="<?php echo strtolower($name[0]); ?>" class="letter">
			<li><?php echo $dateFrom; ?></li>
			<li><?php echo $description; ?></li>
			<li><?php echo $place; ?></li>
			<li><a href="<?php echo $link; ?>">Link</a></li>
			</ul>
			<?php
	endwhile; endif;
	wp_reset_query(); // reset the Loop
}
?>