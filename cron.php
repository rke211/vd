<?php
// run as cron every x minutes/hours
require_once( "config.php" );
$page = 1;
$url = 'https://trialapi.craig.mtcdevserver.com/api/properties?api_key=3NLTTNlXsi6rBWl7nYGluOdkl2htFHug&page[size]=100';
$json = file_get_contents( $url . '&page[number]='.$page );
$data = json_decode( $json );
$importdata = $data->data;
do {
$page = $data->current_page;
  foreach ( $importdata as $row ) {
    $mysqli->query( "INSERT INTO `properties` 
	SET `uuid`='" . $mysqli->real_escape_string( $row->uuid ) . "',
	`county`='" . $mysqli->real_escape_string( $row->county ) . "',
	`country`='" . $mysqli->real_escape_string( $row->country ) . "',
	`town`='" . $mysqli->real_escape_string( $row->town ) . "',
	`desc`='" . $mysqli->real_escape_string( $row->description ) . "',
	`address`='" . $mysqli->real_escape_string( $row->address ) . "',
	`image_full`='" . $mysqli->real_escape_string( $row->image_full ) . "',
	`image_thumb`='" . $mysqli->real_escape_string( $row->image_thumbnail ) . "',
	`lat`='" . $mysqli->real_escape_string( $row->latitude ) . "',
	`long`='" . $mysqli->real_escape_string( $row->longitude ) . "',
	`bedrooms`='" . $mysqli->real_escape_string( $row->num_bedrooms ) . "',
	`bathrooms`='" . $mysqli->real_escape_string( $row->num_bathrooms ) . "',
	`price`='" . $mysqli->real_escape_string( $row->price ) . "',
	`type`='" . $mysqli->real_escape_string( $row->property_type->title ) . "',
	`listing_type`='" . $mysqli->real_escape_string( $row->type ) . "',
	`created`='" . $mysqli->real_escape_string( $row->created_at ) . "',
	`modified`=CURRENT_TIMESTAMP() 
	ON DUPLICATE KEY UPDATE 
	`county`='" . $mysqli->real_escape_string( $row->county ) . "',
	`country`='" . $mysqli->real_escape_string( $row->country ) . "',
	`town`='" . $mysqli->real_escape_string( $row->town ) . "',
	`desc`='" . $mysqli->real_escape_string( $row->description ) . "',
	`address`='" . $mysqli->real_escape_string( $row->address ) . "',
	`image_full`='" . $mysqli->real_escape_string( $row->image_full ) . "',
	`image_thumb`='" . $mysqli->real_escape_string( $row->image_thumbnail ) . "',
	`lat`='" . $mysqli->real_escape_string( $row->latitude ) . "',
	`long`='" . $mysqli->real_escape_string( $row->longitude ) . "',
	`bedrooms`='" . $mysqli->real_escape_string( $row->num_bedrooms ) . "',
	`bathrooms`='" . $mysqli->real_escape_string( $row->num_bathrooms ) . "',
	`price`='" . $mysqli->real_escape_string( $row->price ) . "',
	`type`='" . $mysqli->real_escape_string( $row->property_type->title ) . "',
	`listing_type`='" . $mysqli->real_escape_string( $row->type ) . "',
	`modified`=CURRENT_TIMESTAMP();" );
  }
	$page++;
  $next = $url . '&page[number]='.$page;
  $json = file_get_contents( $next );
  $data = json_decode( $json );
  $importdata = $data->data;
} while ( $page <= $data->last_page);

mysqli_close( $mysqli );