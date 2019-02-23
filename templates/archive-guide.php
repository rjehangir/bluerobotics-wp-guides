<article <?php post_class(); ?>>
    <header>
      <br />
      <span class="woocommerce"><?php woocommerce_breadcrumb(); ?></span>
      <?php 
      $title = get_the_archive_title();
      if (strpos($title, 'Tag') !== false) {
      	// Keep it.
      } else {
      	$title = "All Guides and Tutorials";
      }
      ?>
      
    </header>
    <div class="col-md-9 no-padding">
    	<div class="entry-content row">  
      <?php 
      // OUTPUT SPECIAL TAG CATEGORIES
      if (!get_query_var('paged') && !(strpos($title, 'Tag') !== false)) {
        echo '<p class="home-heading">BlueROV2<a href="/guide-tag/bluerov2/">See All <i class="fa fa-arrow-circle-right fa-fw"></i></a></p>';
        echo do_shortcode('[guide_card tags="bluerov2" columns="4" max_rows="1"]');
      } ?>

      <!-- OUTPUT ALL GUIDES -->
      <p class="home-heading"><?php echo $title; ?></p>
      <div class="row guide-card-row">
<?php while (have_posts()) : the_post(); ?>
	    	<div class="col-md-3">
          <div class="guide-card-wrapper">
  	    		<a href="<?php echo esc_url( get_permalink() ); ?>">
              <div class="guide-card-thumbnail-wrapper">
  		    		  <?php the_post_thumbnail( 'shop_catalog', ['class' => 'img-responsive img-guide-archive'] ); ?>
              </div>
  		    		<h3 class="product-title"><?php the_title(); ?></h3>
  	    		</a>
  	    		<p><?php the_excerpt(); ?></p>
  	      	</div>
          </div>
<?php endwhile; ?>
      </div>
		</div>
    <!-- Pagination Goes Here -->
    <div class="row">
      <div class="small-12 columns">
      <?php
        the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => 'Previous',
            'next_text' => 'Next',
        ) );
      ?>
      </div>
    </div>
	</div>
	<div class="col-md-3">
  		<div data-spy="affix" data-offset-top="170" style="width:291px">
  			<?php get_guide_archive_nav(); ?>
  		</div>
  	</div>
	<footer>
	  <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
	</footer>
</article>
