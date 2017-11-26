<?php

/**
 * Override beTheme Function that did not work.
 *
 * This is needed for two reasons:
 *  1) With this beTheme can add alt tags and such saved in the media library to images within the muffin builder
 *  2) This is used to get the id for images used in tk_make_content_images_responsive, which works like wp_make_content_images_responsive,
 *     only that it covers all images, not just those that have their id saved in their class
 *
 */
function mfn_get_attachment_id_url($url)
{
    $attachment_id = 0;
    if (substr($url, 0, 1) === '/') {
        $url = get_site_url() . $url;
    }

    $dir = wp_upload_dir();
    $urlInUploadDirectory =  strpos($url, $dir['baseurl'] . '/');
    if (false !== $urlInUploadDirectory) {
        $file = basename($url);
        $query_args = array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'value' => $file,
                    'compare' => 'LIKE',
                    'key' => '_wp_attachment_metadata',
                ),
            )
        );
        $query = new WP_Query($query_args);
        if ($query->have_posts()) {
            foreach ($query->posts as $post_id) {

                $meta = wp_get_attachment_metadata($post_id);
                $original_file = basename($meta['file']);
                $cropped_image_files = wp_list_pluck($meta['sizes'], 'file');
                if ($original_file === $file || in_array($file, $cropped_image_files)) {
                    $attachment_id = $post_id;
                    break;
                }
            }
            wp_reset_postdata();
        }
    }
    return $attachment_id;
}

/**
 * Filters 'img' elements in post output to add 'srcset' and 'sizes' attributes.
 *
 * Edited Version of wp_make_content_images_responsive, applies to all images, not just those with id in class
 *
 * @since 4.4.0
 *
 * @see wp_image_add_srcset_and_sizes()
 *
 * @param string $content The raw post content to be filtered.
 * @return string Converted content with 'srcset' and 'sizes' attributes added to images.
 */
function tk_make_content_images_responsive( $content ) {
    if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
        return $content;
    }

    $selected_images = $attachment_ids = array();

    foreach( $matches[0] as $image ) {

        if ( false === strpos( $image, ' srcset=' ) && preg_match( '/src=["\'](.*?)["\']/i', $image, $src ) &&
            ( $attachment_id = mfn_get_attachment_id_url($src[1]) )) {

            /*
             * If exactly the same image tag is used more than once, overwrite it.
             * All identical tags will be replaced later with 'str_replace()'.
             */
            $selected_images[ $image ] = $attachment_id;
            // Overwrite the ID when the same image is included more than once.
            $attachment_ids[ $attachment_id ] = true;
        }
    }

    if ( count( $attachment_ids ) > 1 ) {
        /*
         * Warm object cache for use with 'get_post_meta()'.
         *
         * To avoid making a database call for each image, a single query
         * warms the object cache with the meta information for all images.
         */
        update_meta_cache( 'post', array_keys( $attachment_ids ) );
    }

    foreach ( $selected_images as $image => $attachment_id ) {
        $image_meta = wp_get_attachment_metadata( $attachment_id );
        $content = str_replace( $image, wp_image_add_srcset_and_sizes( $image, $image_meta, $attachment_id ), $content );
    }

    return $content;
}

/**
 * Filters "img" elements in post output to add title and alt if they are missing or empty
 *
 * @param string $content The raw post content to be filtered.
 * @return string Converted content with 'title' and 'alt' attributes added to images, provided they were set in the media library.
 */
function tkAddTitleAndAlt($content)
{
    if (!preg_match_all('/<img [^>]+>/', $content, $matches)) {
        return $content;
    }

    $attachment_ids = array();

    $imagesMissingTitle = array();

    $imagesMissingAlt = array();

    $imagesMissingBoth = array();

    foreach ($matches[0] as $image) {

        if ( false == tkHasAttribute($image, "title") &&
            false == tkHasAttribute($image, "alt") &&
            preg_match( '/src=["\'](.*?)["\']/i', $image, $src ) &&
            ( $attachment_id = mfn_get_attachment_id_url($src[1]) ) ) {

            $imagesMissingBoth[ $image ] = $attachment_id;
            $attachment_ids[ $attachment_id ] = true;

        } else if( false == tkHasAttribute($image, "title") &&
            false != tkHasAttribute($image, "alt") &&
            preg_match( '/src=["\'](.*?)["\']/i', $image, $src ) &&
            ( $attachment_id = mfn_get_attachment_id_url($src[1]) ) ){

            $imagesMissingTitle[ $image ] = $attachment_id;
            $attachment_ids[ $attachment_id ] = true;

        } else if( false != tkHasAttribute($image, "title") &&
            false == tkHasAttribute($image, "alt") &&
            preg_match( '/src=["\'](.*?)["\']/i', $image, $src ) &&
            ( $attachment_id = mfn_get_attachment_id_url($src[1]) ) ){

            $imagesMissingAlt[ $image ] = $attachment_id;
            $attachment_ids[ $attachment_id ] = true;
        }
    }

    if ( count( $attachment_ids ) > 1 ) {
        /* Warm object cache for use with 'get_post_meta()'.
         * To avoid making a database call for each image, a single query
         * warms the object cache with the meta information for all images.
         */
        update_meta_cache( 'post', array_keys( $attachment_ids ) );
    }

    foreach ( $imagesMissingTitle as $image => $attachment_id ) {
        $title = get_the_title( $attachment_id );
        if($title){
            $content = str_replace( $image, tkAddImageAttribute( $image, "title", $title), $content );
        }
    }

    foreach ( $imagesMissingAlt as $image => $attachment_id ) {
        $alt = get_post_meta($attachment_id, "_wp_attachment_image_alt", true);
        if($alt){
            $content = str_replace( $image, tkAddImageAttribute($image, "alt", $alt), $content );
        }
    }

    foreach ( $imagesMissingBoth as $image => $attachment_id ) {
        //we alter the HTML element we're searching for, so we need to remember the altered version
        $imageNew = $image;

        $title = get_the_title( $attachment_id );
        if($title){
            $imageNew = tkAddImageAttribute( $image, "title", $title);
            $content = str_replace( $image, $imageNew, $content );
        }
        $alt = get_post_meta($attachment_id, "_wp_attachment_image_alt", true);
        if($alt){
            $content = str_replace( $imageNew, tkAddImageAttribute($imageNew, "alt", $alt), $content );
        }
    }

    return $content;
}

function tkHasAttribute($element, $attribute){
    return preg_match("/ ".$attribute."=[\"'][^\"']+?[\"']/i", $element);
}

function tkAddImageAttribute($image, $attribute, $value){

    $attr = sprintf( ' %s="%s"', esc_attr($attribute), esc_attr($value) );

    if(preg_match("/ ".$attribute."=[\"'][\"']/i", $image)){
        $image = preg_replace( '/<img ([^>]*?)('.$attribute.'=["\']["\'])([^>]*?)[\/ ]*>/', '<img $1' . $attr . '$3 />', $image );
    }else{
        $image = preg_replace( '/<img ([^>]+?)[\/ ]*>/', '<img $1' . $attr . ' />', $image );
    }
    return $image;
}


function tk_buffer_callback($buffer) {
    return tkAddTitleAndAlt(tk_make_content_images_responsive($buffer));
}

function tk_buffer_start() {
    ob_start("tk_buffer_callback");
}

function tk_buffer_end() {
    ob_end_flush();
}


function tkInstallImageFixes(){
    add_action('wp_head', 'tk_buffer_start', 1);
    add_action('wp_footer', 'tk_buffer_end', 9999);
}
