<?php

require_once('_webcam.php');

add_action( 'add_meta_boxes_comment', 'vgb_comment_add_meta_box_image' );
function vgb_comment_add_meta_box_image()
{
	add_meta_box( 'vgb-comment-title', __( 'Comment Image Upload' ), 'vgb_my_comment_image', 'comment', 'normal', 'high' );
}

function vgb_comment_add_admin_menu_column( $columns )
{
	$columns['vgb_my_comment_image'] = __( 'My webcam upload' );
	return $columns;
}
add_filter( 'manage_edit-comments_columns', 'vgb_comment_add_admin_menu_column' );

function vgb_comment_add_admin_menu_column_data( $column, $comment_ID )
{
	if ( 'vgb_my_comment_image' == $column ) {
		$comment = get_comment($comment_ID);
		vgb_my_comment_image($comment);
		// echo 'Custom Data for ID: ' . $comment_ID;
	}
}
add_filter( 'manage_comments_custom_column', 'vgb_comment_add_admin_menu_column_data', 10, 2 );

?>
