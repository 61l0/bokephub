
<!-- start preview-->
<div class="col-xs-12 col-sm-4 col-md-3 video-preview-col" style="">

    <div class="video-preview">

        <?php if(get_post_meta( get_the_ID(),'duration',true)) {  ?>
          <div class="duration"><?php echo tubeace_duration(get_post_meta( get_the_ID(),'duration',true)); ?></div> 
        <?php } ?>


        <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php echo tubeace_thumb('latest',$post->post_title) ?></a>
    </div>
   

    <div class="video-preview-title">
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php echo tube_get_limited_string($post->post_title,42); ?></a>
    </div>    

    <div class="video-meta-data">
        <div class="pull-left">
            <span class="author">By <?php the_author(); ?></span>
            <?php if(function_exists('the_ratings')) { echo '<br />'.expand_ratings_template('<span class="rating">%RATINGS_IMAGES%</span>', get_the_ID()); }?> 
        </div>
        <div class="pull-right">
            <?php if(function_exists('bawpvc_views_sc')) {  ?><span class="views"><?php echo do_shortcode('[post_views id="'.get_the_id().'""]'); ?></span><?php } ?>
                       
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!-- end preview-->