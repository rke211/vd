<!doctype html>
<html lang="en"><head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <title>Properties</title>
  </head>
  <body>
	  <div class="container-fluid">
<? if(isset($_GET['action']) && $_GET['action']=="delete" && isset($_GET['id'])){
	include_once("delete.php");
}if(isset($_GET['action']) && $_GET['action']=="delete" && isset($_GET['id'])){
	include_once("edit.php");
}
 ?>
		  
		  
		  
   <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">

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
    </table></div>

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
					'processing': true,responsive: 'true', 'order': [[ 2, 'asc' ]],"sDom": '<\"top\">rt<\"bottom\"ilp><\"clear\">',
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