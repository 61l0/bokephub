<?php get_header(); ?>

    <div class="container">
      <div class="row">
        <div class="col-md-9">

          <?php if ( have_posts() ) : ?>
            <?php $i=0; $postCount = 0; while (have_posts()) : the_post(); $i++; $postCount++; ?>
                <?php //if($i==1){echo'<div class="row">';} ?>
                <?php get_template_part( 'preview', get_post_format() ); ?>
                <?php //if($i==4 || $postCount == sizeof($posts)){$i=0;echo'</div>';} ?>
            <?php endwhile; ?>
            <?php if ( function_exists( 'wp_paginate' ) ) : wp_paginate();  else : ?>
            <ul class="pager">
              <li class="previous">
              <?php previous_posts_link() ?>
              </li>
              <li class="next">
              <?php next_posts_link() ?>
              </li>
            </ul>
            <?php endif;  ?>
          <?php else : ?>  

          <header class="entry-header">
            <h1 class="entry-title">Nothing Found</h1>
          </header>
          <div class="entry-content">
            <p>Apologies, but no results were found. Perhaps searching will help find a related post.</p>
            <?php get_search_form(); ?>
          </div><!-- .entry-content -->

        <?php endif; // end have_posts() check ?>

        </div><!-- .col-md-9 -->
        <?php get_sidebar(); ?>
      </div><!-- .row -->
    </div><!-- .container -->
  <?php get_footer(); ?>  