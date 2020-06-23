<?php
require_once("config.php"); 
if(isset($_GET['id'])){
	$mysqli->query("DELETE FROM `properties` WHERE uuid = '".$mysqli->real_escape_string($_GET['id'])."'");
	if($mysqli->affected_rows){
		echo'<div class="alert alert-success">The Property has been deleted.</div> <meta http-equiv="refresh" content="1;URL=index.php" />';
	}else{
		echo'<div class="alert alert-danger">There was an error and the Property specified could not be deleted.</div>';
	}
}else{
	echo'<div class="alert alert-danger">You must provide a Property ID to delete.</div>';
}