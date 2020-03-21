<?php get_header();
$site_base = 'https://example.com';
?>

<link rel="stylesheet" id="stm-lms-taxonomy_archive-css" href="<?php echo $site_base;?>/wp-content/plugins/masterstudy-lms-learning-management-system/assets/css/parts/taxonomy_archive.css?ver=1" type="text/css" media="all">
<link rel="stylesheet" id="stm-lms-courses-css" href="<?php echo $site_base;?>/wp-content/plugins/masterstudy-lms-learning-management-system/assets/css/parts/courses.css?ver=1" type="text/css" media="all">
<link rel="stylesheet" id="stm-lms-courses/style_1-css" href="<?php echo $site_base;?>/wp-content/plugins/masterstudy-lms-learning-management-system/assets/css/parts/courses/style_1.css?ver=1" type="text/css" media="all">
<div class="container" style="padding-top: 20px;">
<style>
.imagexwrapper{
	padding-bottom: 46%;
}
.stm_lms_courses__single--image img {
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: block;
    font-family: "blur-up: always", "object-fit: cover";
    -o-object-fit: cover;
    object-fit: cover !important;
    z-index: 90;
    opacity: 1;
    visibility: visible;
    -webkit-transition: .3s ease;
    -o-transition: .3s ease;
    transition: .3s ease;
}
</style>

<?php
//https://code.tutsplus.com/tutorials/wp_query-arguments-taxonomies--cms-23090
global $post;
	// Get the queried object and sanitize it
global $wp;
$current_slug = $wp->request;

$taxoname = "stm_lms_course_taxonomy";
$operator = 'IN';
$ids = array();
$slug_title = "";
if(isset($_GET['ids'])){
    
	if (preg_match('/(,)/', $_GET['ids'])) {
   $ids = preg_split ("/\,/", $_GET['ids']);  
   $operator = 'AND';
}else{
	 $ids = array($_GET['ids']);
}
   
	$i = 0;
	$terms = array();
	
	foreach($ids as $id){
		 $_term = get_term_by( 'id',  $ids[$i], $taxoname );
		
		 $terms[] = $ids[$i];
		
		 if($i >= 1){
			 $slug_title.= ",".$_term->name;
		 }else{
			 $slug_title  = $_term->name;
		 }
		 
		 
		 
		 $i = $i + 1; 
		//$terms[] = $_term;
		
	}
	
	$query = new WP_Query();

	
	$type = 'stm-courses';
	 $args = array(
    'post_type' => $type,
    'posts_per_page' => -1,
    'tax_query' => array(
            array(
                'taxonomy' => $taxoname,
                'operator' => $operator,
                'terms'    =>  $terms,
            ),
        )
    );
	       
      $courses_content =   generate_courses($args,$slug_title);





}	
	

   
	


?>

   <h2><?php echo $slug_title; ?></h2>
   <div class="stm_lms_courses all_loaded">
      <div class="stm_lms_courses__grid stm_lms_courses__grid_4 stm_lms_courses__grid_center archive_grid" data-pages="1">
	  

		 <?php echo  $courses_content; ?>

		 
      </div>
      <div class="text-center">
         <a href="#" class="btn btn-default stm_lms_load_more_courses" data-offset="1" data-template="courses/grid" data-args="{&quot;per_row&quot;:&quot;4&quot;,&quot;posts_per_page&quot;:&quot;12&quot;,&quot;tax_query&quot;:[{&quot;taxonomy&quot;:&quot;stm_lms_course_taxonomy&quot;,&quot;field&quot;:&quot;term_id&quot;,&quot;terms&quot;:104}],&quot;class&quot;:&quot;archive_grid&quot;}" style="display: none;">
         <span>Load more</span>
         </a>
      </div>
   </div>
</div>
<?php 
function generate_courses($args,$slug_title){
	ob_start();




	    $query = new WP_Query( $args );

        // Output.
        $results = array(
            'total'          => absint( $query->found_posts ),
            'current_page'   => absint( $args['page'] ) ? absint( $args['page'] ) : 1,
            'pages'          => array(),
            'total_pages'    => 1,
            'threads'        => array(),
        );
        if ( $query->found_posts > 1 ) {
            $results['pages'] = range( 1, absint( ceil( absint( $query->found_posts ) / absint( $args['limit'] ) ) ) );
            $results['total_pages'] = absint( count( $results['pages'] ) );
        }
		
		//print_r($results);
		//print_r($courses);
        $courses = $query->posts;
        $courses = is_array( $courses ) ? $courses : array();


      
	
		
	
        // Loop all posts to get post object.
        foreach ( $courses as $_post ) {

 if ( has_post_thumbnail($_post->ID) ) {
    $featured_image = get_the_post_thumbnail_url($_post->ID);
}




$post_id = (!empty($_post->ID)) ? $_post->ID : get_the_ID();
$sale_price = get_post_meta( $post_id , 'sale_price' , true);
$price = get_post_meta( $post_id , 'price' , true);
$duration_info = get_post_meta( $post_id , 'duration_info' , true);
	
		?>
		
				          <div class="stm_lms_courses__single stm_lms_courses__single_animation has-sale style_1 ">
            <div class="stm_lms_courses__single__inner">
               <div class="stm_lms_courses__single--image">
                  <a href="<?php echo $_post->guid; ?>" class="heading_font" data-preview="معاينة هذه الدورة">
                     <div>
                        <div class="imagexwrapper"><img src="<?php echo $featured_image; ?>"></div>  
                     </div>
                  </a>
               </div>
               <div class="stm_lms_courses__single--inner">
                  <div class="stm_lms_courses__single--terms">
                     <div class="stm_lms_courses__single--term">
                      <?php echo $slug_title; ?>
                     </div>
                  </div>
                  <div class="stm_lms_courses__single--title">
                     <a href="<?php echo $site_base;?>/courses/c-course-from-0-to-hero/">
                        <h5><?php echo $_post->post_title; ?></h5>
                     </a>
                  </div>
                  <div class="stm_lms_courses__single--meta">
                     <div class="stm_lms_courses__hours">
                        <i class="stmlms-lms-clocks"></i>
                        <span><?php echo $duration_info; ?></span>
                     </div>
                     <div class="stm_lms_courses__single--price heading_font">
                        <span><?php echo $price; ?></span>
                        <strong><?php echo $sale_price; ?></strong>
                     </div>
                  </div>
               </div>

            </div>
         </div>
		

		
		<?
		}
		
	$out = ob_get_clean();
	return $out;
}

?>
<?php get_footer(); ?>