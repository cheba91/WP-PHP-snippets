<?php
//Shorten product excerpt & try to get additional desc if there is none
add_action('woocommerce_after_shop_loop_item_title', 'shorten_product_excerpt', 35);
function shorten_product_excerpt()
{
    global $post;
    $limit = 10;
    $title = $post->post_title;
    $text = $post->post_excerpt;
    //if there is no short desc. try getting additional short desc.
    if (empty($text)) {
        $text = get_post_meta($post->ID, 'additional_short_description', true);
    };
    if (str_word_count($text, 0) > $limit) {
        $arr = str_word_count($text, 2);
        $pos = array_keys($arr);
        $text = substr($text, 0, $pos[$limit]) . '...';
    }
    echo '<h2>' . $title . '</h2><span class="excerpt"><p>' . $text . '</p></span>';
}

//Get sub category only 1lv deep
add_shortcode('display-sub-categories', 'display_product_subcategories');
function display_product_subcategories()
{
    if (is_product_category()) {

        $term_id  = get_queried_object_id();
        $taxonomy = 'product_cat';

        // Get subcategories of the current category
        $terms    = get_terms([
            'taxonomy'    => $taxonomy,
            'hide_empty'  => false,
            'parent'      => $term_id
        ]);
        if (!empty($terms)) {

            $output = '<h2 class="sub-cat-title">Subcategories:</h2><ul class="subcategories-list">';

            foreach ($terms as $term) {
                $term_link = get_term_link($term, $taxonomy);

                $output .= '<li class=single-sub-cat "' . $term->slug . '"><a href="' . $term_link . '">' . $term->name . '</a></li>';
            };
            echo $output . '</ul>';
        }
    };
};
// Edit woo product tab
add_filter('woocommerce_product_tabs', 'woo_custom_description_tab', 98);
function woo_custom_description_tab($tabs)
{
    $tabs['description']['callback'] = 'woo_custom_description_tab_content';    // Custom description callback

    return $tabs;
}
// Custom woo tab
function woo_custom_description_tab_content()
{
    global $post;
    $text = get_post_meta($post->ID, 'additional_description', true);
    if (empty($text)) echo false;
    echo '<h2>Desc title</h2>';
    echo '<p>' . $text . '</p>';
}
