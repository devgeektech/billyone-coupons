<?php
/**
 * Plugin Name: Coupons
 * Plugin URI: 
 * Description: This plugins generate bulk coupons, create youtube video's, store used coupons details etc.
 * Version: 1.0
 * Author: Geektech
 */

define( 'EASYCOUPON__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( EASYCOUPON__PLUGIN_DIR . 'include.php' );

function easy_coupon_plugin_menu() {
  add_menu_page( 'Coupons', 'Coupons', 'manage_options', 'easy-coupons', 'coupons_listing');
  add_submenu_page('easy-coupons', 'Add Coupon','Add Coupon','manage_options','add_new','add_new_function');
  //add_submenu_page('easy-coupons', 'Coupons List','Coupons List','manage_options','coupons_list','coupons_listing');
  add_submenu_page('easy-coupons', 'Youtube Video\'s','Youtube Video\'s','manage_options','videos','youtube_videos');
  add_submenu_page('easy-coupons', 'Invalid Coupon\'s','Invalid Coupon\'s','manage_options','invalid-coupons','invalid_coupons');
  
}
add_action( 'admin_menu', 'easy_coupon_plugin_menu' );


if(isset($_GET['act']) && trim($_GET['act']) == 'delete_expire'){

    global $wpdb;

    if(isset($_GET['expiry_date']) && trim($_GET['expiry_date']) !=''){

        $date = date('Y-m-d', strtotime(trim($_GET['expiry_date'])));
        $prefix_name = $wpdb->prefix;

        $coupons = $wpdb->get_results("SELECT id FROM ".$prefix_name."easy_couppons  WHERE date(expire_date) = '".$date."'", ARRAY_A);

                    if($coupons){
                    
                    $wpdb->query("DELETE FROM ".$prefix_name."easy_couppons WHERE date(expire_date) = '".$date."'");

            }
    }
}elseif(isset($_GET['act']) && trim($_GET['act']) == 'delete'){

    global $wpdb;

    if(isset($_GET['cid']) && (int)$_GET['cid'] > 0){
        
        $prefix_name = $wpdb->prefix;
        $cid = (int)$_GET['cid'];

        $coupons = $wpdb->get_row("SELECT id FROM ".$prefix_name."easy_couppons  WHERE id = '".$cid."'", ARRAY_A);

                    if($coupons){
                    
                     $wpdb->query("DELETE FROM ".$prefix_name."easy_couppons WHERE id = '".$cid."'");
                     
                    header('Location: ./admin.php?page=easy-coupons');

            }
    }
}elseif(isset($_GET['vact']) && trim($_GET['vact']) == 'delete'){

    global $wpdb;

    if(isset($_GET['vid']) && (int)$_GET['vid'] > 0){
        
        $prefix_name = $wpdb->prefix;
        $vid = (int)$_GET['vid'];

        $videos = $wpdb->get_row("SELECT id FROM ".$prefix_name."easy_couppons_youtube   WHERE id = '".$vid."'", ARRAY_A);

                    if($videos){
                    
                     $wpdb->query("DELETE FROM ".$prefix_name."easy_couppons_youtube WHERE id = '".$vid."'");
                     
                    header('Location: ./admin.php?page=videos');

            }
    }
}



function add_new_function(){
if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    require_once( EASYCOUPON__PLUGIN_DIR . 'genrate-coupon-list.php' );
}



register_activation_hook(__FILE__,'easy_coupons_activate');



function easy_coupons_activate(){

    global $wpdb;
    global $table_prefix;
    $tab=$table_prefix.'easy_couppons';
    $sql="CREATE TABLE $tab (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupons` varchar(255) NOT NULL,
  `watched_video` varchar(255) NULL NULL,  
  `status` tinyint(5) NOT NULL DEFAULT 0,
  `watched_date` date NULL NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `expire_date` date NULL DEFAULT NULL,
   
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4";
    $wpdb->query($sql);

    // Create invalid entered coupons

    $tab_name=$table_prefix.'easy_couppons_invalid';
    $sql2="CREATE TABLE $tab_name (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(255) NOT NULL,  
  `created_date` date NULL DEFAULT NULL,
   
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4";

    $wpdb->query($sql2);



// Create youtube table

  $tab_youtube=$table_prefix.'easy_couppons_youtube';
  $sql_youtube="CREATE TABLE $tab_youtube (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_url` text NULL DEFAULT NULL,
  `video_image_url` text NULL DEFAULT NULL,
  `expire_date` date NULL, 
  `created_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
   
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4";

      $wpdb->query($sql_youtube);


}

function coupons_listing(){
 include('listing.php');
}

function invalid_coupons(){
 include('invalid-coupons.php');
}


add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {

   echo '<script type="text/javascript">
           var my_ajax_url = "' . admin_url('admin-ajax.php') . '";
         </script>';
}


add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');
function callback_for_setting_up_scripts() {
    wp_register_style( 'coupons_pl', plugins_url('assets/youtube_video.css',__FILE__ ));
    wp_enqueue_style( 'coupons_pl' );

    wp_register_style( 'coupons_pl2', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css');
    wp_enqueue_style( 'coupons_pl2' );

    wp_enqueue_script( 'coupons_pl_sc1', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'coupons_pl_sc', plugins_url('assets/youtube_video.js',__FILE__ ), array( 'jquery' ) );
    

        
}


function youtube_videos(){
 include('video_listings.php');
}


function get_youtube_video_id($url)
{
    if (stristr($url,'youtu.be/'))
        {
            preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID); return $final_ID[4];

         }
    else 
        {
            @preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $IDD);
         return $IDD[5];
     }
}


add_action('wp_ajax_add_youtube_video', 'add_youtube_video');
add_action('wp_ajax_nopriv_add_youtube_video', 'add_youtube_video');

function add_youtube_video()
{
    $json_res = array();
    if(isset($_POST['add_video_url_info']) && trim($_POST['add_video_url_info'])== ''){

        $json_res['error'] = 'Please enter youtube video urls.';
    }else if(isset($_POST['add_video_url_info']) && trim($_POST['add_video_url_info'])!= ''){

           $video_url = trim($_POST['add_video_url_info']);
           

           if(!preg_match('#https?://(?:www\.)?youtube\.com/watch\?v=([^&]+?)#', $video_url, $matches))
           {
              $json_res['error'] = 'Please enter valid youtube video urls.'; 
           }
           
           if(empty($json_res)){

                $vid = get_youtube_video_id($video_url);

                $video_image_url = 'https://img.youtube.com/vi/'.$vid.'/mqdefault.jpg';
                          
                global $wpdb;
                global $table_prefix;
                $video_tab = $table_prefix. 'easy_couppons_youtube';
                 
                $query=$wpdb->insert($video_tab, array('video_url' => $video_url,'video_image_url'=>$video_image_url));  
          
                $wpdb->query($query);
                $json_res['success'] = true;
            } 
    }
  print(json_encode($json_res));  
  die;
}


// Validate Coupon Code

add_action('wp_ajax_validate_coupon_code', 'validate_coupon_code');
add_action('wp_ajax_nopriv_validate_coupon_code', 'validate_coupon_code');

function validate_coupon_code()
{

    $json_res = array();
    if(isset($_POST['coupon_code_input']) && trim($_POST['coupon_code_input'])== ''){

        $json_res['error'] = 'Please enter coupon code.';
    }else if(isset($_POST['coupon_code_input']) && trim($_POST['coupon_code_input'])!= ''){

           $coupon_input = trim($_POST['coupon_code_input']);
           $video_id = trim($_POST['video_id']);       

           
           if(!empty($coupon_input)){               
                          
                global $wpdb;
                global $table_prefix;
                $coupon_table = $table_prefix. 'easy_couppons';
                $coupon = $wpdb->get_row("SELECT id, coupons, expire_date FROM ".$coupon_table." WHERE coupons = '".$coupon_input."'", ARRAY_A); 
                
                if($coupon){

                    if(isset($coupon['expire_date']) && $coupon['expire_date'] >=date('Y-m-d')){

                    $video_table = $table_prefix. 'easy_couppons_youtube';
                    $video_info = $wpdb->get_row("SELECT id, video_url FROM ".$video_table." WHERE id = '".$video_id."'", ARRAY_A);

                     if($video_info){
                       
                        $wpdb->update($coupon_table, array('status'=>1, 'watched_date'=>date('Y-m-d'), 'watched_video'=>$video_info['video_url']), array('id'=>$coupon['id']));

                        
                        $wpdb->update($video_table, array('expire_date'=>$coupon['expire_date']), array('id'=>$video_id));

                        $set_co_time = time()+31556926;

                        $cname = 'you_tube_'.$video_id;
                        
                        @setcookie($cname, $video_id, $set_co_time, "/");

                        $json_res['success'] = true;
                    }

                  }else{

                        $json_res['error'] = 'coupon code expired.';
                  }
               }else{

                    
                    $invalid_coupon_table = $table_prefix. 'easy_couppons_invalid';

                    $invalid_query=$wpdb->insert($invalid_coupon_table, array('coupon_code' => $coupon_input,'created_date'=>date('Y-m-d')));  
          
                    $wpdb->query($invalid_query);
                    $json_res['error'] = 'Invalid coupon code.';

               }




            } 
    }
    


  print(json_encode($json_res));  
  die;
}

// END



// CREATE SHORT CODE FOR FRONT END PAGE/POST

function show_you_tube_video( $atts ) {

    $return_txt = '';
    if(isset($atts) && isset($atts['id']) && (int)$atts['id'] > 0){

        global $wpdb;        
        $prefix_name = $wpdb->prefix;
        $vid = (int)$atts['id'];        

        $video = $wpdb->get_row("SELECT id,video_url,video_image_url, expire_date FROM ".$prefix_name."easy_couppons_youtube   WHERE id = '".$vid."'", ARRAY_A);


        if($video){

            $you_tube_vid = get_youtube_video_id($video['video_url']);

            /*echo '<pre>';
            print_r($_COOKIE);
            echo '</pre>';*/



            if(isset($_COOKIE['you_tube_'.$video['id']]) && $video['expire_date'] >= date('Y-m-d')){

                $return_txt = '<div class="video-container"><iframe class="embed-responsive-item" src="https://www.youtube.com/embed/'.$you_tube_vid.'?rel=0"></iframe></div>';
            }else if(isset($_COOKIE['you_tube_'.$video['id']]) && $video['expire_date'] < date('Y-m-d')){

                $return_txt = 'Sorry, coupon expired.';
            }else if(!isset($_COOKIE['you_tube_'.$video['id']])){

                $return_txt = '<div class="video-container"><img src="'.$video['video_image_url'].'" onclick=showVideo(\''.$video['id'].'\')></div>
                <!--- Modal --->
<div class="container">
<div class="modal fade" id="show_coupon" tabindex="-1" role="dialog" aria-labelledby="show_couponModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="add_videoModalLabel">Enter Coupon Code</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="form-group">
      <input type="text" name="coupon_code" id="coupon_code" placeholder="Enter coupon code"  class="form-control">

    </div>
      <br><br>
      <button name="validate_coupon" id="validate_coupon" class="btn btn-primary">Submit</button>
    
      </div>
    </div>
  </div>
</div>

<!------- end modal ------>

                ';
            }


        }else{

            $return_txt = 'Video not exist.';
        }

    }

    return $return_txt;
    
}
add_shortcode('show_video', 'show_you_tube_video');