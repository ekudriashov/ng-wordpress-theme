<?php
/**
 * ng-WordPress customizing WP REST API response.
 * @package ng-WordPress
 */
/**
 * Extending REST API response
 */
// add featured image url to REST api response
// function ngwordpress_rest_prepare( $data, $post, $context ) {
// 	$_data = $data->data;
// 	$thumbnail_id = get_post_thumbnail_id( $post->ID );
// 	$thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'full' );
// 	$_data['featured_thumb_url'] = $thumbnail[0];
// 	$data->data = $_data;
// 	return $data;
// }
// add_filter( 'rest_prepare_post', 'ngwordpress_rest_prepare', 10, 3 );

function ngwordpress_rest_prepare( $data, $post, $context ) {
	$featured_image_id = $data->data['featured_media']; // get featured image id
	$featured_image_url = wp_get_attachment_image_src( $featured_image_id, 'full' ); // get url of the original size
	if( $featured_image_id ) {
		$data->data['featured_thumb_url'] = $featured_image_url[0];
	}

	// $categories = get_the_category( $post->ID );
	$data->data['post_categories'] = get_the_category( $post->ID );

	$data->data['post_comments_count'] = wp_count_comments( $post->ID );

	$data->data['post_author_name'] = get_the_author_meta( 'display_name', $data->data['author'] );
	$data->data['post_author_bio'] = get_the_author_meta( 'description', $data->data['author'] );
	$data->data['post_author_avatar'] = get_the_author_meta( 'be_custom_avatar', $data->data['author'] );


	unset( $data->data['featured_media'] ); // remove the featured_media field
	
	unset( $data->data['modified'] );
	unset( $data->data['modified_gmt'] );


	return $data;
}
add_filter( 'rest_prepare_post', 'ngwordpress_rest_prepare', 10, 3 );