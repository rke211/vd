<?php
$mysqli = new mysqli("localhost", "rkenet1_vd", "ryan123", "rkenet1_vd");
if(mysqli_connect_error()) {
	trigger_error("Failed to connect to to MySQL: " . mysql_connect_error(), E_USER_ERROR);
}
?>