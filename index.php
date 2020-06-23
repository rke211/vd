<? include_once("config.php"); ?>
<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<title>Properties</title>
</head>
<body>
<div class="container-fluid mt-2"> 
	<? if(isset($_GET['action'])){ ?>
	<a href="index.php"   class="btn btn-danger float-right">Back To Search</a>
	<? }else{ ?>
	<a data-toggle="collapse" href="#manageProperty" role="button" aria-expanded="false" aria-controls="manageProperty"   class="btn btn-success float-right">Add Property</a>
	<? } ?>
  <div class="clearfix"></div>
  <?
  if ( isset( $_POST[ 'btnSave' ] ) ) {
    $sql = [];
    foreach ( $_POST as $key => $value ) {
      if ( $key != "btnSave" && $key != "uuid" ) {
        $value = $mysqli->real_escape_string( $value );
        $sql[] = "`" . $key . "` = '" . $value . "'";
      }
    }

    if ( isset( $_FILES[ 'upload' ][ "tmp_name" ] ) && $_FILES[ 'upload' ][ "tmp_name" ] != "" ) {
      $target_dir = "img/";
      $uploadOk = 1;
      $ext = explode( ".", $_FILES[ 'upload' ][ "name" ] );
      $target_file = $target_dir . time() . "." . end( $ext );
      $thumbnail_url = $target_dir . "thumb_" . time() . "." . end( $ext );
      $imageFileType = strtolower( end( $ext ) );
      if ( $imageFileType != "jpg" && $imageFileType != "jpeg" ) {
          echo '<div class="alert alert-danger">Currently only supports JPEG</div>';
        $uploadOk = 0;
      } else {
        if ( move_uploaded_file( $_FILES[ 'upload' ][ "tmp_name" ], $target_file ) ) {
          $image = imagecreatefromjpeg( $target_file );
          $imgResized = imagescale( $image, 100, 100 );
          imagejpeg( $imgResized, $thumbnail_url );
          $sql[] = "`image_full` = '" . $target_file . "'";
          $sql[] = "`image_thumb` = '" . $thumbnail_url . "'";
        } else {
          echo '<div class="alert alert-danger">Could not save image</div>';
        }
      }
    }
if(!isset($_POST['uuid'])){
	$sql[] = "`uuid` = '" . generateToken() . "'";
}
    $sql_string = ( isset( $_POST[ 'uuid' ] ) ? 'UPDATE' : 'INSERT INTO' ) . ' `properties` SET ' . implode( ",", $sql ) . ( isset( $_POST[ 'uuid' ] ) ? " WHERE uuid = '" . $_POST[ 'uuid' ] . "'" : '' );
    $mysqli->query( $sql_string );
    if ( $mysqli->insert_id || $mysqli->affected_rows ) {
      echo '<div class="alert alert-success">Property Saved</div><meta http-equiv="refresh" content="1;URL=index.php" />';
    } else {
      echo '<div class="alert alert-danger">Could not save changes</div>';
    }

  }
  ?>
  <div class="collapse<?=(isset($_POST['btnSave']) || (isset($_GET['action']) && $_GET['action']=="edit") ? ' show':''); ?>" id="manageProperty">
    <div class="card mt-3">
      <div class="card-header">Property Manager</div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
          <?
          $propdata == false;
          if ( isset( $_GET[ 'id' ] ) && isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == "edit" ) {
            $property = $mysqli->query( "SELECT * FROM properties WHERE uuid ='" . $mysqli->real_escape_string( $_GET[ 'id' ] ) . "';" );
            if ( $property->num_rows ) {
              $propdata = $property->fetch_array( MYSQLI_ASSOC );
              echo '<input type="hidden" name="uuid" value="' . $propdata[ 'uuid' ] . '">';
            }
          }
          ?>
          <div class="row">
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold">County</label>
              <input type="text" class="form-control" name="county" <?=($propdata ? ' value="'.$propdata['county'].'"':''); ?> required>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold">Country</label>
              <input type="text" class="form-control" name="country"<?=($propdata ? ' value="'.$propdata['country'].'"':''); ?> required>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold">Town</label>
              <input type="text" class="form-control" name="town"<?=($propdata ? ' value="'.$propdata['town'].'"':''); ?> required>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold">Postcode</label>
              <input type="text" class="form-control" name="postcode"<?=($propdata ? ' value="'.$propdata['postcode'].'"':''); ?> required>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold">Price</label>
              <div class="input-group mb-3">
                <div class="input-group-prepend"> <span class="input-group-text">£</span> </div>
                <input type="number" min="1" step="1" class="form-control" name="price" <?=($propdata ? ' value="'.$propdata['price'].'"':''); ?> required>
              </div>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold">Property Type</label>
              <select name="type" class="form-control" required>
                <option="">Select one
                </option>
                <option<?=($propdata && $propdata['type']=="Semi-detached" ? ' selected="selected"':''); ?>>Semi-detached</option>
                <option<?=($propdata && $propdata['type']=="Bungalow" ? ' selected="selected"':''); ?>>Bungalow</option>
                <option<?=($propdata && $propdata['type']=="Cottage" ? ' selected="selected"':''); ?>>Cottage</option>
                <option<?=($propdata && $propdata['type']=="Terraced" ? ' selected="selected"':''); ?>>Terraced</option>
                <option<?=($propdata && $propdata['type']=="End of Terrace" ? ' selected="selected"':''); ?>>End of Terrace</option>
                <option<?=($propdata && $propdata['type']=="Flat" ? ' selected="selected"':''); ?>>Flat</option>
                <option<?=($propdata && $propdata['type']=="Detached" ? ' selected="selected"':''); ?>>Detached</option>
              </select>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold">Bedrooms</label>
              <select name="bedrooms" class="form-control" required>
                <option value="">Select one</option>
                <?
                for ( $i = 1; $i < 100; $i++ ) {
                  echo '<option' . ( $propdata && $propdata[ 'bedrooms' ] == $i ? ' selected="selected"' : '' ) . '>' . $i . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold">Bathrooms</label>
              <select name="bathrooms" class="form-control" required>
                <option value="">Select one</option>
                <?
                for ( $i = 1; $i < 100; $i++ ) {
                  echo '<option' . ( $propdata && $propdata[ 'bathrooms' ] == $i ? ' selected="selected"' : '' ) . '>' . $i . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-12">
              <label class="font-weight-bold">Address</label>
              <input type="text" class="form-control" name="address"<?=($propdata ? ' value="'.$propdata['address'].'"':''); ?> required>
</textarea>
            </div>
            <div class="form-group col-12">
              <label class="font-weight-bold">Description</label>
              <textarea name="desc" class="form-control" required> <?=($propdata ? $propdata['desc']:''); ?>
</textarea>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold d-block">Property Listing</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="listing_type" id="inlineRadio1" value="rent"<?=($propdata && $propdata['listing_type']=="rent" ? ' checked="checked"':''); ?>>
                <label class="form-check-label" for="inlineRadio1">For Rent</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="listing_type" id="inlineRadio2" value="sale"<?=($propdata && $propdata['listing_type']=="sale" ? ' checked="checked"':''); ?>>
                <label class="form-check-label" for="inlineRadio2"> For Sale</label>
              </div>
            </div>
            <div class="form-group col-12 col-md-6">
              <label class="font-weight-bold d-block">Image Upload (JPEG)</label>
              <div class="custom-file">
                <input type="file" name="upload" class="custom-file-input" id="inputGroupFile01" accept="image/jpeg">
                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
              </div>
            </div>
          </div>
          <button class="btn btn-success btn-block" name="btnSave" type="submit">Save</button>
        </form>
      </div>
    </div>
  </div>
  <?
  if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == "delete" && isset( $_GET[ 'id' ] ) ) {
    include_once( "delete.php" );
  }elseif ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == "view" && isset( $_GET[ 'id' ] ) ) {
    	$property = $mysqli->query( "SELECT * FROM properties WHERE uuid ='" . $mysqli->real_escape_string( $_GET[ 'id' ] ) . "';" );
	  $nicename=["uuid"=>"Property ID","county"=>"County","country"=>"Country","town"=>"Town","desc"=>"Description","postcode"=>"Postcode","address"=>"Address","lat"=>"Latitude","long"=>"Longitude","bedrooms"=>"Number of Bedrooms","bathrooms"=>"Number of Bathrooms","price"=>"Price","type"=>"Property Type","listing_type"=>"Listing Type","created"=>"Listed On","modified"=>"Last Updated",];
            if ( $property->num_rows ) {
              	$propdata = $property->fetch_array( MYSQLI_ASSOC );
				echo'<div class="card mt-3">
					<div class="card-header">Property Details</div>
					<div class="card-body"><div class="row">';
					foreach($nicename as $key=>$value){
						echo'<div class="col-3 font-weight-bold">'.$value.'</div>';
						echo'<div class="col-9">'.($key=="price" ? '£'.number_format($propdata[$key]): $propdata[$key]).'</div>';
					}
				echo'<div class="col-6 text-center font-weight-bold">Full Image:<br><img src="'.$propdata['image_full'].'" alt="Full Image"></div>
				<div class="col-6 text-center font-weight-bold">Thumbnail Image:<br><img src="'.$propdata['image_thumb'].'" alt="Thumbnail"></div>';
				echo'</div>
				<a href="?action=edit&id='.$propdata['uuid'].'" class="btn btn-success btn-block mt-3">Edit Property</a>
				</div>
				</div>';
			}else{
				echo'<div class="alert alert-danger">The propert you specified could not be found</div>';
			}
  }
  ?>
  <table id="example" class="table table-striped table-bordered mt-3" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>Image</th>
        <th>County</th>
        <th>Country</th>
        <th>Bedrooms</th>
        <th>Bathrooms</th>
        <th>Price</th>
        <th>Type</th>
        <th>Added On</th>
        <th class="no-sort">Actions</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>Image</th>
        <th>county</th>
        <th>country</th>
        <th>bedrooms</th>
        <th>bathrooms</th>
        <th>price</th>
        <th>type</th>
        <th>Added On</th>
        <th class="no-sort">Actions</th>
      </tr>
    </tfoot>
  </table>
</div>

<!-- Optional JavaScript --> 
<!-- jQuery first, then Popper.js, then Bootstrap JS --> 
<script
			  src="https://code.jquery.com/jquery-3.5.1.min.js"
			  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
			  crossorigin="anonymous"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> 
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> 
<script>var dataTable = $('#example').DataTable( {
					'processing': true,responsive: 'true', 'order': [[ 2, 'asc' ]],
   stateSave: true,
					'serverSide': true,
					'LengthChange': false,
					'bLengthChange': false,
        'iDisplayLength': 10,
					'ajax':{
						url :'get_listings.php',
						type: 'post',
						error: function(){ 
							$('#example').append('<tbody class=\"employee-grid-error\"><tr><th colspan=\"3\">No data found in the server</th></tr></tbody>');
							$('#example').css('display','none');
							
						}
					}
			} );</script>
</body>
</html>