
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
  <h1 class="wp-heading-inline">Generate Coupons</h1>
</div>
</div>


<div class="container">
<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="genratecoupoun" class="form-inline">

 <div class="row">

  
  <div class="form-group">
   <input type="text" class="form-control pickdate" name="add_expiry_date" id="add_expiry_date" placeholder="Choose Date">
 </div>

    <div class="form-group">
        <input class="count" type="number" name="coupon_count" min="1" placeholder="Enter number of coupons want to generate"> 
        <button class="generate btn btn-primary" id="generate">Generate</button>
        <input type="hidden" name="action" value="genratecoupoun">  
    </div>

</div>
</form>
</div> 
<script>
  $(document).ready(function() {
    $('#example').DataTable();
} );

</script>

<script type="text/javascript">
  $(document).ready(function(){
    var num = $(".count").val();
    $(".generate").click(function(e) {
        var genratecoupoun = $('#genratecoupoun');
        var datavalue = genratecoupoun.serialize();
        
        $.ajax({
          url:genratecoupoun.attr('action'),
          data:datavalue, // form data
          type:genratecoupoun.attr('method'), // POST
          dataType: 'json',
          success:function(data){
             if(data['status']){
                window.location = "<?= site_url('wp-admin/admin.php?page=easy-coupons');?>";
             }
          }
        });
        return false;
      });
    });


  $(document).ready(function(){
      $('#add_expiry_date, #expiry_date').datepicker({
        "setDate": new Date(),        
        "autoclose": true
      });
    })
</script>

</body>
</html>