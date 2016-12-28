<?php get_header(); ?>

    <div class="container">
      <div class="row">
        <div class="col-md-12">


        <?php while ( have_posts() ) : the_post(); ?>

          <?php edit_post_link( 'Edit Video', '<span class="edit-link">', '</span>' ); ?>
          <div class="video-header">

            <h1 class="video-title"><?php the_title(); ?></h1>
            
              <?php tubeace_video_player(); ?>
            
          </div><!-- .video-header -->

          <?php tubeace_misc(1); ?>
          <?php tubeace_misc(2); ?>

          <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#info">Video Info</a></li>
            <li><a href="#comments">Comments</a></li>
          </ul>

          <div id='content' class="tab-content">
            <div class="video-data row-fluid tab-pane active" id="info">

              <div class="col-md-8">
                <?php tubeace_sponsor_link(); ?>
                <?php the_content(); ?>
                <?php the_tags('Tags: ', ', ', '<br />'); ?>
                Category: <?php the_category(', '); ?><br />
                Uploaded By: <?php the_author(); ?>
        
              </div>

              <div class="col-md-4 pull-right">
                <?php if(function_exists('bawpvc_views_sc')) {  ?><span class="views"><?php echo do_shortcode('[post_views id="'.get_the_id().'""]'); ?></span><?php } ?>
                <time datetime="<?php echo get_the_date("Y-m-d"); ?>">Published on <?php the_date(); ?></time>
                <?php echo get_the_term_list( $post->ID, "performer", 'Performers: ', ', ', '' ) ?>
                <?php if(function_exists('the_ratings')) { the_ratings(); } ?>                
              </div>
              
            </div>

            
            <div class="video-data row-fluid tab-pane" id="comments">
              <?php comments_template( '', true ); ?>
            </div>

          </div>

          <script>
            jQuery('#myTab a').click(function (e) {
              e.preventDefault()
              jQuery(this).tab('show')
            })          
          </script>


        <?php endwhile; // end of the loop. ?>          


        <?php 
          //related videos
          $categories = get_the_category($post->ID);
          $cats = $categories[0]->cat_ID;

          $args = array(
            'cat' => $cats,
            'posts_per_page' => 12,
          );

          $wp_query = new WP_Query($args); 

          echo'<h3>Related Videos</h3>';

          if ( $wp_query ->have_posts() ) {

            $i=0; $postCount = 0; while (have_posts()) : the_post(); $i++; $postCount++;
                if($i==1){echo'<!--start row--><div class="row">';} 
                get_template_part( 'preview', get_post_format() );
                
                if($i==6 || $postCount == $wp_query->post_count){$i=0;echo'</div><!--/ row-->';}
            endwhile;

          }

          wp_reset_postdata(); 

          ?>

        </div><!-- .col-md-12 -->
        
      </div><!-- .row -->  
      <?php get_sidebar(); ?>
    </div><!-- .container -->
    <?php get_footer(); ?>  