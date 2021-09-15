
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
  <h1 class="wp-heading-inline">Coupons List</h1>
</div>
</div>


 <div class="row">
        <div class="col-lg-12">&nbsp;

        </div>
  </div>

<div class="container">
<form action="<?php echo site_url() ?>/wp-admin" method="get" class="form-inline">

<div class="form-group">      
<input type="hidden" name="page" value="easy-coupons">
<input type="hidden" name="act" value="delete_expire">
    
      <input type="text" class="form-control pickdate" name="expiry_date" id="expiry_date" placeholder="Enter expiry date">
</div>

      <div class="form-group">
        <button class="btn btn-primary"  type="submit" name="delete_coupon">Delete</button>
      </div>
</form>
</div>
<br><br>
<div class="container">
	<table  id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>                
                <th class="col-md-2 text-center">Coupons Code</th>
                <th class="col-md-2 text-center">Watched Video</th>
                <th class="col-md-1 text-center">Status</th>
                <th class="col-md-2 text-center">Coupon Used Date</th> 
                <th class="col-md-2 text-center">Date Created</th>
                <th class="col-md-2 text-center">Expire Date</th>
                <th class="col-md-1 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
		<?php
	         
      // 1st Method - Declaring $wpdb as global and using it to execute an SQL query statement that returns a PHP object
         global $wpdb;
         global $table_prefix;
         $table=$table_prefix.'easy_couppons';
         $results = $wpdb->get_results( "SELECT * FROM $table ");
         foreach($results as $result){

          $status = 'Not Used';
          $use_date = 'n/a';
          $video_url = 'n/a';

          if(isset($result->watched_video) && !empty($result->watched_video)):
            $video_url = '<a href="'.$result->watched_video.'" target="_blank">Click to View</a>';
           endif;

           if(isset($result->status) && (int)$result->status == 1):
            $status = 'Used';
           endif;

           if(isset($result->watched_date) && !empty($result->watched_date)):
            $use_date = $result->watched_date;
           endif; 


          ?>

            <tr>                
                <td class="col-md-2 text-center"><?= $result->coupons; ?></td>
                <td class="col-md-2 text-center"><?=$video_url;?></td>
                <td class="col-md-1 text-center"><?=$status;?></td>
                <td class="col-md-2 text-center"><?=$use_date;?></td>
                <td class="col-md-2 text-center"><?= $result->created_date; ?></td>
                <td class="col-md-2 text-center"><?= $result->expire_date; ?></td>
                <td class="col-md-1 text-center"><a href="<?php echo site_url('wp-admin/admin.php?page=easy-coupons&cid='.$result->id.'&act=delete');?>">Delete</a></td>
            </tr>
            <?php } ?>
        </tbody>
       
    </table>

</div>
<script>
  $(document).ready(function() {
    $('#example').DataTable();
} );

</script>

<script type="text/javascript">
  $(document).ready(function(){
      $('#add_expiry_date, #expiry_date').datepicker({
        "setDate": new Date(),        
        "autoclose": true
      });
    })
</script>

</body>
</html>
