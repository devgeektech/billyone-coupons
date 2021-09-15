<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap4.min.css">
  
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
  
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
</head>
<body>

<div class="container"><div class="row">
  <h1 class="wp-heading-inline">Youtube Video's List</h1>
</div>
</div>


 <div class="row">
        <div class="col-lg-10 text-right"><a href="javascript:void(0);" data-toggle="modal" data-target="#add_video">Add Video</a></div>
  </div>

<div class="container">

</div>
<br><br>
<div class="container">
	<table  id="videos" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr> 
                <th class="col-md-1 text-center">ID</th>               
                <th class="col-md-5 text-center">Youtube Video Urls</th>
                <th class="col-md-2 text-center">Short Code</th>
                <th class="col-md-2 text-center">Date Added</th>                
                <th class="col-md-2 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
		<?php
	         
      // 1st Method - Declaring $wpdb as global and using it to execute an SQL query statement that returns a PHP object
         global $wpdb;
         global $table_prefix;
         $table=$table_prefix.'easy_couppons_youtube';
         $results = $wpdb->get_results( "SELECT * FROM $table ");
         foreach($results as $result){      


          ?>

            <tr>
                <td class="col-md-1 text-center"><?= $result->id;?></td>                
                <td class="col-md-5 text-center"><?= $result->video_url; ?></td>
                <td class="col-md-2 text-center">[show_video id='<?= $result->id; ?>']</td>
                <td class="col-md-2 text-center"><?= date('m-d-Y', strtotime($result->created_date)); ?></td>                 
                <td class="col-md-2 text-center"><a href="<?php echo site_url('wp-admin/admin.php?page=videos&vid='.$result->id.'&vact=delete');?>">Delete</a></td>
            </tr>
            <?php } ?>
        </tbody>
       
    </table>

</div>

<!--- Modal --->
<div class="container">
<div class="modal fade" id="add_video" tabindex="-1" role="dialog" aria-labelledby="add_videoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="add_videoModalLabel">Enter Youtube Video Url</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="form-group">
      <input type="text" name="add_video_url" id="add_video_url" placeholder="Enter youtube video urls"  class="form-control">

    </div>
      <br><br>
      <button name="add_video_button" id="add_video_button" class="btn btn-primary">Save</button>
    
      </div>
    </div>
  </div>
</div>

<!------- end modal ------>

<script type="text/javascript">
  $(document).ready(function() {
    $('#videos').DataTable({searching: false, paging: false, info: false});

    $("#add_video_button").on('click', function() {

        
        var add_video_url = $('#add_video_url').val();
        
        
        $.ajax({          
          url:"<?php echo site_url(); ?>/wp-admin/admin-ajax.php",
          data: { 'action': 'add_youtube_video', 'add_video_url_info': add_video_url }, 
          type:'POST',
          dataType: 'json',         
          success:function(data){

            $("#add_video .text-danger").remove();
            
            //console.log(data['success']);
             if(data['success']){
              location.reload(true);
             }else if(data['error']){
                $("#add_video_url").after('<p class="text-danger">'+data['error']+'</p>');
             }
          }

        });
       
      });


} );

</script>
</body>
</html>