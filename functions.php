<?php

// Register the Javascript

function javascript_scripts()
{
  wp_deregister_script('jquery');
  wp_register_script('jquery', get_template_directory_uri() . '/assets/js/libs/jquery-3.4.1.js', array(), "3.4.1", true);
  wp_register_script('plugins-script', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), false, true);
  wp_register_script('main-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'plugins-script'), false, true);

  wp_enqueue_script('main-script');

  wp_localize_script('my-script-handle', 'frontEndAjax', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('ajax_nonce')
  ));
}
add_action('wp_enqueue_scripts', 'javascript_scripts');

// Register the CSS
function style_css()
{
  wp_register_style('style', get_template_directory_uri() . '/style.css', array(), filemtime(get_stylesheet_directory() . '/style.css'), false);
  wp_enqueue_style('style');
}
add_action('wp_enqueue_scripts', 'style_css');

// Remove block-library/style.min.css
function wpassist_remove_block_library_css()
{
  wp_dequeue_style('wp-block-library');
}
add_action('wp_enqueue_scripts', 'wpassist_remove_block_library_css');

// Activate SVG as upload format
function add_file_types_to_uploads($file_types)
{
  $new_filetypes = array();
  $new_filetypes['svg'] = 'image/svg+xml';
  $file_types = array_merge($file_types, $new_filetypes);
  return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');

// ACF Custom Options pages
if (function_exists('acf_add_options_page')) {

  acf_add_options_page(array(
    'page_title'   => 'Footer',
    'menu_title'  => 'Footer',
    'menu_slug'   => 'footer',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ));

  acf_add_options_page(array(
    'page_title'   => 'General',
    'menu_title'  => 'General',
    'menu_slug'   => 'link-settings',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ));
}

// Remove useless itens from header
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

// Add support to Thumbnails
add_theme_support('post-thumbnails');

// Remove itens from ADMIN
function remove_menu_items()
{

  remove_menu_page('themes.php');
  remove_submenu_page('options-general.php', 'options-media.php');
  remove_submenu_page('options-general.php', 'options-discussion.php');
  remove_submenu_page('options-general.php', 'akismet-key-config');
  // remove_submenu_page('admin.php', 'wp_mailjet_options_campaigns_menu');

  //  remove_menu_page( 'tools.php' );

  //  remove_menu_page( 'edit.php' );

  // remove_menu_page('edit-comments.php');

  // remove_menu_page('upload.php');

  // remove_menu_page( 'plugins.php' );

  //  remove_menu_page( 'edit.php?post_type=acf-field-group' );

}
add_action('admin_menu', 'remove_menu_items', 999);

// Excluir a pasta node_modules da exportação do All In One WP Export
add_filter('ai1wm_exclude_content_from_export', function ($exclude_filters) {
  $exclude_filters[] = 'themes/editora-codice/node_modules';
  return $exclude_filters;
});

// Custom Post Type - Product

//hook into the init action and call create_book_taxonomies when it fires
// add_action('init', 'create_topics_hierarchical_taxonomy', 0);

// //create a custom taxonomy name it topics for your posts

// function create_topics_hierarchical_taxonomy()
// {

//   // Product custom taxonomy and post type

//   $labels = array(
//     'name' => _x('Product Category', 'taxonomy general name'),
//     'singular_name' => _x('Product Category', 'taxonomy singular name'),
//     'search_items' =>  __('Search Product Categories'),
//     'all_items' => __('All Product Category'),
//     'parent_item' => __('Product Category parent'),
//     'parent_item_colon' => __('Product Category parent:'),
//     'edit_item' => __('Edit Product Category'),
//     'update_item' => __('Update Product Category'),
//     'add_new_item' => __('Add New Product Category'),
//     'new_item_name' => __('New Product Category'),
//     'menu_name' => __('Product Category'),
//   );

//   register_taxonomy('product_category', array('products'), array(
//     'hierarchical' => true,
//     'labels' => $labels,
//     'show_ui' => true,
//     'show_admin_column' => true,
//     'query_var' => true,
//     'rewrite' => array('slug' => 'product/category'),
//   ));
// }

// function custom_post_type_product()
// {
//   register_post_type('products', array(
//     'label' => 'Products',
//     'description' => 'Products',
//     'public' => true,
//     'show_ui' => true,
//     'show_in_menu' => true,
//     'capability_type' => 'post',
//     'map_meta_cap' => true,
//     'has_archive' => 'custom-branded-products',
//     'hierarchical' => false,
//     'rewrite' => array('slug' => 'product', 'with_front' => true),
//     'query_var' => true,
//     'supports' => array('title', 'editor', 'page-attributes', 'post-formats'),
//     'taxonomies' => array('product_category'),

//     'labels' => array(
//       'name' => 'Products',
//       'singular_name' => 'Product',
//       'menu_name' => 'Products',
//       'add_new' => 'Add new',
//       'add_new_item' => 'Add new product',
//       'edit' => 'Editar',
//       'edit_item' => 'Edit product',
//       'new_item' => 'New product',
//       'view' => 'View Product',
//       'view_item' => 'View Product',
//       'search_items' => 'Search Products',
//       'not_found' => 'No product found',
//       'not_found_in_trash' => 'No product found in trash',
//       'has_archive' => true,
//     )
//   ));
// }
// add_action('init', 'custom_post_type_product');


// function set_views($post_ID)
// {

//   $key = 'views';
//   $count = get_post_meta($post_ID, $key, true); //retrieves the count

//   if ($count == '') { //check if the post has ever been seen

//     //set count to 0
//     $count = 0;

//     //just in case
//     delete_post_meta($post_ID, $key);

//     //set number of views to zero
//     add_post_meta($post_ID, $key, '0');
//   } else { //increment number of views
//     $count++;
//     update_post_meta($post_ID, $key, $count);
//   }
// }

// //keeps the count accurate by removing prefetching
// remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

// function track_custom_post_watch($post_ID)
// {
//   //you can use is_single here, to track all your posts. Here, we're traking custom post 'watch'
//   if (!is_singular('products')) return;

//   if (empty($post_ID)) {

//     //gets the global post
//     global $post;

//     //extracts the ID
//     $post_ID = $post->ID;
//   }

//   //calls our previously defined methos
//   set_views($post_ID);
// }
// add_action('wp_head', 'track_custom_post_watch');


// // Infinite Scroll
// function infinite_scroll_search_posts()
// {

//   $loopFile = $_POST['loop_file'];
//   $paged = $_POST['page_no'];
//   $action = $_POST['what'];
//   $value = $_POST['value'];
//   $cat_id = $_POST['cat'];

//   if ($action == 'category_name') {
//     $arg = array(
//       's' => $value,
//       'paged' => $paged,
//       'post_status' => 'publish',
//       'post_type' => 'products',
//       'tax_query' => array(
//         array(
//           'taxonomy' => 'product_category',
//           'field' => 'slug',
//           'terms' => $cat_id
//         )
//       )
//     );
//   } elseif ($action == 'search') {
//     $arg = array(
//       's' => $value,
//       'paged' => $paged,
//       'post_status' => 'publish',
//       'post_type' => 'products',
//     );
//   } else {
//     $arg = array(
//       'paged' => $paged,
//       'post_status' => 'publish',
//       'post_type' => 'products'
//     );
//   }

//   query_posts($arg);
//   get_template_part($loopFile);

//   exit;
// }
// add_action('wp_ajax_infinite_scroll', 'infinite_scroll_search_posts');
// add_action('wp_ajax_nopriv_infinite_scroll', 'infinite_scroll_search_posts');

// function template_chooser($template)
// {
//   global $wp_query;
//   $post_type = get_query_var('post_type');
//   if ($wp_query->is_search && $post_type == 'products') {
//     return locate_template('archive-search.php');  //  redirect to archive-search.php
//   }
//   return $template;
// }
// add_filter('template_include', 'template_chooser');


/**
 * Extend WordPress search to include custom fields
 *
 * https://adambalee.com
 */

/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
// function cf_search_join($join)
// {
//   global $wpdb;

//   if (is_search()) {
//     $join .= ' LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
//   }

//   return $join;
// }
// add_filter('posts_join', 'cf_search_join');

// /**
//  * Modify the search query with posts_where
//  *
//  * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
//  */
// function cf_search_where($where)
// {
//   global $pagenow, $wpdb;

//   if (is_search()) {
//     $where = preg_replace(
//       "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
//       "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)",
//       $where
//     );
//   }

//   return $where;
// }
// add_filter('posts_where', 'cf_search_where');

// /**
//  * Prevent duplicates
//  *
//  * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
//  */
// function cf_search_distinct($where)
// {
//   global $wpdb;

//   if (is_search()) {
//     return "DISTINCT";
//   }

//   return $where;
// }
// add_filter('posts_distinct', 'cf_search_distinct');
// function calculate_rate()
// {

//   $product_id = $_POST["product_id"];
//   $postal_code = $_POST["postal_code"];
//   $state_code = $_POST["state_code"];
//   $amount = (float) $_POST["amount"];

//   try {
//     // Get UPS endpoint
//     $endpointurl = get_ups_api_endpoint(true);

//     $credentials = get_ups_credentials(
//       get_option('upsplugin_option_api_key'),
//       get_option('upsplugin_option_username'),
//       get_option('upsplugin_option_password'),
//       get_option('upsplugin_option_account_number')
//     );


//     $package_shippiment_info = get_product_dimensions($product_id);

//     $shipper_data = get_shipper($package_shippiment_info["ship_point"], "72FW53", $package_shippiment_info["state_code"]);
//     $ship_to_data = get_ship_to($postal_code, $state_code);



//     $package_sizes = (object) [
//       'width'  => $package_shippiment_info['boxcarton_size_width'],
//       'height' => $package_shippiment_info['boxcarton_size_height'],
//       'length' => $package_shippiment_info['boxcarton_size_height'],
//       'weight' => $package_shippiment_info['boxcarton_weight']
//     ];

//     $quantity_box = (int) $package_shippiment_info['boxcarton_quantity'];
//     $pkgcount = intdiv($amount, $quantity_box);

//     if ($amount % $quantity_box != 0) {
//       $pkgcount++;
//     }


//     wp_send_json(
//       create_ups_request_shippiment_price(
//         $endpointurl,
//         $credentials,
//         $shipper_data,
//         $ship_to_data,
//         $package_sizes,
//         $pkgcount
//       )
//     );
//   } catch (Exception $e) {
//     wp_send_json($e);
//   }
// }

// add_action('wp_ajax_calculate_rate', 'calculate_rate');
// add_action('wp_ajax_nopriv_calculate_rate', 'calculate_rate');

// function get_product_dimensions($product_id)
// {
//   global $wpdb;
//   $query = $wpdb->prepare(
//     "SELECT
//               REPLACE(meta_key, 'shipping_printing_shipping_details_', '') as meta_key,
//               meta_value FROM wp_postmeta
//                 where (meta_key LIKE 'shipping_printing_shipping_details_%')
//                   and meta_value <> ''
//                   AND post_id = %d",
//     $product_id
//   );

//   $resultSet = $wpdb->get_results($query, OBJECT);

//   $result = array();
//   foreach ($resultSet as $row) {
//     $result[$row->meta_key] = $row->meta_value;
//   }

//   return $result;
// }
