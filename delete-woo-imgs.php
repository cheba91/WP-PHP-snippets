add_action("before_delete_post", "delete_post_images", 10, 1);

function delete_post_images($post_id)
{
    global $wpdb;
    $args = array(
        'post_parent' => $post_id,
        'post_type'   => 'attachment',
        'numberposts' => -1,
        'post_status' => 'any'
    );
    $childrens = get_children($args);
    if ($childrens) {
        foreach ($childrens as $attachment) {
            wp_delete_attachment($attachment->ID, true);
            $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id = " . $attachment->ID);
            wp_delete_post($attachment->ID, true);
        }
    }
}
