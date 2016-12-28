<?php get_header(); ?>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>Porn Star List</h2>

			<?php 
			
			$list = '';
			$tags = get_terms( 'performer' );

			$groups = array();
			if( $tags && is_array( $tags ) ) {
			    foreach( $tags as $tag ) {
			        $first_letter = strtoupper( $tag->name[0] );
			        $groups[ $first_letter ][] = $tag;
			    }
			    if( !empty( $groups ) ) {
			        foreach( $groups as $letter => $tags ) {

			        	$tagsCount = $tags;

						$i=0; $postCount = 0;

			            $list .= '<h2>' . apply_filters( 'the_title', $letter ) . '</h2>';
			            foreach( $tags as $tag ) {

			  				$i++;
			  				$postCount++;

			            	if($i==1){$list .=' <!--start row--><div class="row">';} 
			                $url = esc_attr( get_term_link( $tag ) );
			                $name = apply_filters( 'the_title', $tag->name );
			                $list .= '<div class="col-md-3"><a href="' . $url . '">' . $name . '</a></div>';
			                if($i==6 || $postCount == count($tagsCount)){$i=0;$list .='</div><!--/ row-->';}

			            }
			        }
			    }
			} else $list .= '<p>Sorry, but no tags were found</p>';

			echo $list;

			?>

        </div><!-- .col-md-9 -->
        <?php get_sidebar(); ?>
      </div><!-- .row -->  
    </div><!-- .container -->
    <?php get_footer(); ?>  