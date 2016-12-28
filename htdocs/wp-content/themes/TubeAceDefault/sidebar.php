
        <div class="col-md-3">
          <div class="well sidebar-nav">

            <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
              <div class="widget-area" role="complementary">
                <?php dynamic_sidebar( 'sidebar-1' ); ?>
              </div>
            <?php endif; ?>

              
            <div class="tag-cloud">
              <h2>Popular Tags</h2>
              <?php wp_tag_cloud('smallest=7&largest=16&number=100'); ?>
            </div>
          </div><!--/.well -->
        </div><!--/span-->	