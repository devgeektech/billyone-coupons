<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
  
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
</head>
<body>

<div class="container"><div class="row">
  <h1 class="wp-heading-inline">Invalid Coupons List</h1>
</div>
</div>


 <div class="row">
        <div class="col-lg-12">&nbsp;

        </div>
  </div>

<div class="container">
	<table  id="invalid_coupons" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>                
                <th class="col-md-2 text-center">Coupons Code</th>                
                <th class="col-md-2 text-center">Entered Date</th>
            </tr>
        </thead>
        <tbody>
		<?php
	         
      // 1st Method - Declaring $wpdb as global and using it to execute an SQL query statement that returns a PHP object
         global $wpdb;
         global $table_prefix;
         $table=$table_prefix.'easy_couppons_invalid ';
         $results = $wpdb->get_results( "SELECT * FROM $table ");
         foreach($results as $result){  ?>

            <tr>                
                <td class="col-md-2 text-center"><?= $result->coupon_code; ?></td>                
                <td class="col-md-2 text-center"><?= date('Y-m-d', strtotime($result->created_date)); ?></td>                
            </tr>
            <?php } ?>
        </tbody>
       
    </table>

</div>
<script>
  $(document).ready(function() {
    $('#invalid_coupons').DataTable({searching: false, info: false});
} );

</script>
</body>
</html>