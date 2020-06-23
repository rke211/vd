<?php 
include_once("config.php"); 
header('Content-Type: application/json');
$requestData= $_REQUEST;
$columns = array( 
// datatable column index  => database column name
	0 =>'uuid', 
	1 => 'country'
);

// getting total number records without any search
$sql = "SELECT * FROM `properties` ";
$query=$mysqli->query($sql);
$totalData = $query->num_rows;
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT * FROM `properties` ";


/* if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( firstname LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR surname LIKE '".$requestData['search']['value']."%' )";
} */
$query=$mysqli->query($sql);
$totalFiltered = $query->num_rows;
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
error_log($sql);
$query=$mysqli->query($sql);

$data = array();

while( $row=$query->fetch_array(MYSQLI_ASSOC) ) { 
	$nestedData=array(); 
	$nestedData[] = '<img src="'.$row['image_thumb'].'" alt="thumbnail"/>';
	$nestedData[] = $row['county'];
	$nestedData[] = $row['country'];
	$nestedData[] = $row['bedrooms'];
	$nestedData[] = $row['bathrooms'];
	$nestedData[] = '£'.number_format($row['price']);
	$nestedData[] = $row['type'];
	$nestedData[] = date("d-M-Y H:i",strtotime($row['created']));
	$nestedData[] = '<a href="#" class="btn btn-success">Edit</a> <a href="#" class="btn btn-danger">Delete</a>';
	
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