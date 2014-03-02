<?php

/**
 * Firefox_OS_Bookmark
 *
 * @package   Firefox_OS_Bookmark
 * @author    Mte90 <mte90net@gmail.com>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Mte90
 */
define( 'WP_USE_THEMES', false );

function FindWPConfig( $dirrectory ) {
	global $confroot;
	foreach ( glob( $dirrectory . "/*" ) as $f ) {
		if ( basename( $f ) == 'wp-load.php' ) {
			$confroot = str_replace( "\\", "/", dirname( $f ) );
			return true;
		}

		if ( is_dir( $f ) ) {
			$newdir = dirname( dirname( $f ) );
		}
	}

	if ( isset( $newdir ) && $newdir != $dirrectory ) {
		if ( FindWPConfig( $newdir ) ) {
			return false;
		}
	}
	return false;
}

global $confroot;
FindWPConfig( dirname( dirname( __FILE__ ) ) );
include_once $confroot . "/wp-load.php";


require('../../../wp-load.php');

//Get options
$manifest = ( array ) get_option( 'firefox-os-bookmark' );

//Execute the resize
if ( isset( $manifest[ 'icon' ] ) ) {
	//Local path
	$clean_url = ABSPATH . str_replace( get_bloginfo( 'url' ), '', $manifest[ 'icon' ] );
	//Absolute url for icon
	$url = parse_url( dirname( $manifest[ 'icon' ] ) );
	$img = wp_get_image_editor( $clean_url );
	unset( $manifest[ 'icon' ] );
	$manifest[ 'icons' ] = array();

	//Resize the icon
	if ( !is_wp_error( $img ) ) {

		$sizes_array = array(
			array( 'width' => 16, 'height' => 16, 'crop' => true ),
			array( 'width' => 32, 'height' => 32, 'crop' => true ),
			array( 'width' => 48, 'height' => 48, 'crop' => true ),
			array( 'width' => 60, 'height' => 60, 'crop' => true ),
			array( 'width' => 64, 'height' => 64, 'crop' => true ),
			array( 'width' => 90, 'height' => 90, 'crop' => true ),
			array( 'width' => 120, 'height' => 120, 'crop' => true ),
			array( 'width' => 128, 'height' => 128, 'crop' => true ),
			array( 'width' => 256, 'height' => 256, 'crop' => true ),
		);

		$resize = $img->multi_resize( $sizes_array );

		foreach ( $resize as $row ) {
			$manifest[ 'icons' ][ $row[ 'width' ] ] = $url[ 'path' ] . '/' . $row[ 'file' ];
		}
	}
}
unset( $manifest[ 'alert' ] );
$manifest[ 'installs_allowed_from' ] = "*";
//Get locales info
if ( isset( $manifest[ 'locales' ] ) ) {
	$locales = $manifest[ 'locales' ];
	unset( $manifest[ 'locales' ] );
	$locales_clean = array();
	foreach ( $locales as $key => $value ) {
		$locales_clean[ $value[ 'language' ] ] = array( 'name' => $value[ 'name' ], 'description' => $value[ 'description' ] );
	}
	$manifest[ 'locales' ] = $locales_clean;
}

//Replace the "
$manifest[ 'developer' ][ 'name' ] = str_replace( '"', "'", $manifest[ 'developer' ][ 'name' ] );

//Clean JSON
$manifest_ready = str_replace( '\\', '', json_encode( $manifest ) );

//Set the mime type
header( 'Content-type: application/x-web-app-manifest+json' );

//Clean and print
echo str_replace( '"installs_allowed_from":"*"', '"installs_allowed_from":["*"]', $manifest_ready );
