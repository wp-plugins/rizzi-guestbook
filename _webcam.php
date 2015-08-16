<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // end if

function getMyUploadDir() {
	//error_log("in getMyUploadDir()", 0);
	$path = realpath('.') .'/wp-content/uploads/webcam/';
	$path = str_replace('/wp-admin', '', $path);
	//error_log("DIR='" . $path . "'", 0);
	/* Now we can use $path in all our Filesystem API method calls */
	if(!is_dir($path)) {
		/* directory didn't exist, so let's create it */
		mkdir($path, 0755, true);
	}
	return $path;
}

function getMySiteUploadDir() {
	//error_log("in getMySiteUploadDir()", 0);
	$path = get_site_url() .'/wp-content/uploads/webcam/';
	$path = str_replace('/wp-admin', '', $path);
	//error_log("DIR='" . $path . "'", 0);
	return $path;
}

function vgb_my_comment_image($comment) 
{
	$dir = getMySiteUploadDir();
	$serverdir = getMyUploadDir();
	$html= "<p>";
	//$html .= "dir='$dir'<br/>";
	$id = $comment->comment_ID;
	//$html .= "id='$id' <br/>";
	$file = get_comment_meta($comment->comment_ID, 'photoElement', true);
	//$html .= "file='$file'<br/>";
	if (!empty($file)) {
		$html .= "<!-- file-absolute='" . $serverdir . $file . "' file-www='" . $dir .$file . "' -->";
		// check filesize
 		if (filesize($serverdir . $file) > 0) {
			$html .= '<img src="' . $dir . $file . '" class="showUpload" />';
			//$html .= "<br/>Juhu!";
		}
		else {
			$html .= '<!-- filesize is empty -->';
		}
	}
	else {
		$html .= "<!-- No webcam image available! -->";
	}
	$html .= "</p>";
	echo $html;
}

function save_comment_image($comment_id, $comment_obj) {
	//error_log("in save_comment_image()", 0);
	$photo = $_POST['photoElement'];
	$dir = getMyUploadDir();
	$filename = 'photoElement_' . $comment_id . '.png';
	$photo = str_replace('data:image/png;base64,', '', $photo);
	$photo = base64_decode($photo);
	file_put_contents($dir . $filename, $photo);
	
	add_comment_meta($comment_id, 'photoElement', $filename);
}

/*************************************************************************/
/******************Output the webcam-image of the user********************/
/*************************************************************************/
function comment_image( $column_name, $comment_id ) {
	if( 'comment-image' == strtolower( $column_name ) ) {
			// check that some comment image exists
			if( 0 != ( $comment_image_data = get_comment_meta( $comment_id, 
				'comment_image', true ) ) ) {
				$image_url = $comment_image_data['url'];
				// check filesize
				//if (filesize($image_url) > 0) {
					$html = '<img src="' . $image_url . '" width="150" />';
					echo $html;
				//}
		} // end if
	} // end if
} // end comment_image

/*************************************************************************/
/************************Output the webcam-thing for the user*************/
/*************************************************************************/
function vgb_my_webcam_image() 
{
	$url = vgb_get_data_url() . '/templates/mywebcam.html';
	$mywebcam = file_get_contents($url) ;
	//echo "$url";
	echo "$mywebcam";
}

?>
