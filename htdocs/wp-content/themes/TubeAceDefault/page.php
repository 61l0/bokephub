<?php get_header(); ?>

    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <h2><?php the_title(); ?></h2>
          	<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
			<?php endwhile; // end of the loop. ?>
			<?php comments_template(); ?>

        </div><!-- .col-md-9 -->
        <?php get_sidebar(); ?>
      </div><!-- .row -->  
    </div><!-- .container -->
<?php get_footer(); ?>  