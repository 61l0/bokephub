<?php get_header(); ?>

    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <h2>Categories</h2>

			<?php 
			$i=0; $postCount = 0;

			$args = array(
			  'orderby' => 'name',
			  'order' => 'ASC'
			  );
			$categories = get_categories($args);
			  foreach($categories as $category) { 
			  	$i++;
			  	$postCount++;
			  	if($i==1){echo'<!--start row--><div class="row">';} 
			    echo '<div class="col-md-3"><h3><a href="' . get_category_link( $category->term_id ) . '">' . $category->name.'</a></h3>';
			    if($category->description){echo $category->description;}
				echo'</div>';
				if($i==4 || $postCount == sizeof($categories)){$i=0;echo'</div><!--/ row-->';}
			} 

			?> 
        </div><!-- .col-md-9 -->
        <?php get_sidebar(); ?>
      </div><!-- .row -->  
    </div><!-- .container -->
    <?php get_footer(); ?>  