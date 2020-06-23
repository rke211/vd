<?php
$mysqli = new mysqli("localhost", "rkenet1_vd", "ryan123", "rkenet1_vd");
if(mysqli_connect_error()) {
	trigger_error("Failed to connect to to MySQL: " . mysql_connect_error(), E_USER_ERROR);
}


function generateToken()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $token = '';
        for ($i = 0; $i < 8; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        $token .= "-";
        for ($i = 0; $i < 4; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        $token .= "-";
        for ($i = 0; $i < 4; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        $token .= "-";
        for ($i = 0; $i < 4; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        $token .= "-";
        for ($i = 0; $i < 12; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        return $token;
    }
?>