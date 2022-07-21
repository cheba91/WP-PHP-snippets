// Manually change image for yoast
function change_yoast_image($presentation)
{
  global $post;
  if (isset($post)) {
    $img_id = get_post_thumbnail_id($post->ID);
    if ($img_id) {
      $mime_type = get_post_mime_type($img_id);
      if ($mime_type != 'video/mp4') {
        $image_url = wp_get_attachment_url($img_id);
        list($width, $height, $type) = getimagesize($image_url);
      }
      // minimum pixels for sharing (Facebook is 200px)
      if ($width > 200 && $height > 200) {
        $presentation->open_graph_images = [
          [
            'url' => $image_url,
            'width' => $width,
            'height' => $height,
            'type' => $type
          ]
        ];
      }
    }
  }
  return $presentation;
}
add_filter('wpseo_frontend_presentation', 'change_yoast_image', 30);

// Manually change meta desc & desc length
$comment_text = strlen($full_comment_text) > 137 ? substr($full_comment_text, 0, 137) . "..." : $full_comment_text;
 update_post_meta($topic_id, '_yoast_wpseo_metadesc', $comment_text);
