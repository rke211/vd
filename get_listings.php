<?php 
include_once("config.php"); 
header('Content-Type: application/json');
$requestData= $_REQUEST;
$columns = array( 
// datatable column index  => database column name
	0 =>'image_thumb', 
	1 => 'county', 
	2 => 'country', 
	3 => 'bedrooms', 
	4 => 'bathrooms', 
	5 => 'price', 
	6 => 'type', 
	7 => 'created', 
	8 => 'uuid'
);

// getting total number records without any search
$sql = "SELECT * FROM `properties` ";
$query=$mysqli->query($sql);
$totalData = $query->num_rows;
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT * FROM `properties` ";


 if( !empty($requestData['search']['value']) ) {  
	$sql.=" WHERE ( county LIKE '".$mysqli->real_escape_string($requestData['search']['value'])."%' ";    
	$sql.=" OR country LIKE '".$mysqli->real_escape_string($requestData['search']['value'])."%'";    
	$sql.=" OR type LIKE '".$mysqli->real_escape_string($requestData['search']['value'])."%' )";
} 
$query=$mysqli->query($sql);
$totalFiltered = $query->num_rows;
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$query=$mysqli->query($sql);

$data = array();

while( $row=$query->fetch_array(MYSQLI_ASSOC) ) { 
	$nestedData=array(); 
	$nestedData[] = '<a href="'.$row['image_full'].'" target="_blank"><img src="'.$row['image_thumb'].'" alt="'.$row['country'].' thumbnail" width="100" height="100" /></a>';
	$nestedData[] = $row['county'];
	$nestedData[] = $row['country'];
	$nestedData[] = $row['bedrooms'];
	$nestedData[] = $row['bathrooms'];
	$nestedData[] = '£'.number_format($row['price']);
	$nestedData[] = $row['type'];
	$nestedData[] = date("d-M-Y H:i",strtotime($row['created']));
	$nestedData[] = '<a href="?action=view&id='.$row['uuid'].'" class="btn btn-success">Learn More</a> <a href="?action=delete&id='.$row['uuid'].'" class="btn btn-danger">Delete</a>';
	
	$data[] = $nestedData;
}


$json_data = array(
			"draw"            => intval( $requestData['draw'] ), 
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data  
			);

echo json_encode($json_data);  

?>