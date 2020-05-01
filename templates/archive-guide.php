<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Blue_Robotics
 * @since 1.0.0
 */

get_header();
?>
  <?php 
  $title = get_the_archive_title();
  if (strpos($title, 'Tag') !== false) {
    // Keep it.
  } else {
    $title = "All Guides and Tutorials";
  }
  ?>
  <section class="breadcrumb_section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">               
                    <ol class="breadcrumb ">
                        <li class="breadcrumb-item">
                            <a href="<?php echo site_url(); ?>">Back to Home</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
  </section>
  <section id="primary" class="content-area blog_content_first_section">            
    <div class="container">
    
      <main id="main" class="site-main">

        <article <?php post_class(); ?>>
          <div class="row">
            <div class="col-md-9 no-padding">
            <?php 
            // OUTPUT SPECIAL TAG CATEGORIES
            if (!get_query_var('paged') && !(strpos($title, 'Tag') !== false)) {
			  // ALL SECTIONS SHOULD GO AFTER THIS LINE
				
			  // START SECTION //
			  // To edit this section, change the title in the h2 tag, the "View All" link address, and the guide_card tags.
			  // To make a new section, copy the START through END lines and paste them above or below, but within the "if" statement.
              echo '<div class="row"><div class="col-md-6"><div class="para second-head "><h2>BlueROV2</h2></div></div><div class="col-md-6"><div class="d-none d-md-block para p_category"><h6><a href="/guide-tag/bluerov2/">View All</h6></div></div></div>';
              echo do_shortcode('[guide_card tags="bluerov2" columns="4" max_rows="1"]');
			  // END SECTION //
			  
			  // DON'T CHANGE ANYTHING AFTER THIS LINE
            } ?>

            <!-- OUTPUT ALL GUIDES -->
            <div class="row"><div class="col-md-6"><div class="para second-head "><h2><?php echo $title; ?></h2></div></div><div class="col-md-6"><div class="d-none d-md-block para p_category"><h6><a href="/guide-tag/bluerov2/">View All</h6></div></div></div>
            <div class="row guide-card-row">
      <?php while (have_posts()) : the_post(); if (get_post_status() != 'private') : ?>
              <div class="col-md-3">
                <div class="guide-card-wrapper para">
                  <a href="<?php echo esc_url( get_permalink() ); ?>">
                    <div class="guide-card-thumbnail-wrapper">
                      <?php the_post_thumbnail( 'shop_catalog', ['class' => 'img-responsive img-guide-archive'] ); ?>
                    </div>
                    <h5><?php the_title(); ?></h5>
                  </a>
                  <div class="page-description"><?php the_excerpt(); ?></div>
                  </div>
                </div>
      <?php endif; endwhile; ?>
            </div>
			<br />
            <!-- Pagination Goes Here -->
            <div class="row text-center">
              <div class="text-center">
				  <h4>
              <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => 'Previous',
                    'next_text' => 'Next',
                ) );
              ?>
  				  </h4>
              </div>
            </div>
			<br /><br /><br />
          </div>
          <div class="col-md-3">
              <div data-spy="affix" data-offset-top="170" style="width:100%">
                <?php get_guide_archive_nav(); ?>
              </div>
            </div>
          <footer>
            <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
          </footer>
        </div>
        </article>
      </main><!-- #main -->
    </div>
  </section><!-- #primary -->

<?php
get_footer();
