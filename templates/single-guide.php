<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Blue_Robotics
 * @since 1.0.0
 */

get_header();
$overlayTitle = get_field( "overlay_title" );
$thumbnail_url = get_the_post_thumbnail_url();
$mobile_image_url = get_field('mobile_image');
// print_r($thumbnail_url);
if($overlayTitle) {
?>
    <!-- <section class="blog_banner_section">
        <div class="hero">
            <div class="hero-overlay-center blog_hero_overlay">
                <div class="container">                  
                    <h2><?php //echo $overlayTitle; ?></h2>                 
                </div>
            </div>
            <img src="<?php //echo $thumbnail_url; ?>" alt="" class="hero-image img-fluid d-none d-md-block">
            <img src="<?php //echo $mobile_image_url['url']; ?>" alt="<?php echo ($mobile_image_url['alt']) ? $mobile_image_url['alt'] : ""; ?>" class="hero-image img-fluid  d-xl-none d-md-none">
        </div>
    </section> -->
<section class="blog_banner_section">
	<div class="hero">
		<div class="Center_txt_outer">
        <img src="<?php echo $thumbnail_url; ?>" alt="" class="hero-image img-fluid d-none d-md-block">
            <img src="<?php echo $mobile_image_url['url']; ?>" alt="<?php echo ($mobile_image_url['alt']) ? $mobile_image_url['alt'] : ""; ?>" class="hero-image img-fluid  d-xl-none d-md-none">
			<div class="center_txt_inner">                                                 
				<h1><?php echo $overlayTitle; ?></h1>
			</div>
		</div>
					
	</div>
</section>
<?php } ?>
    <section class="breadcrumb_section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">               
                    <ol class="breadcrumb blog_breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo site_url().'/learn'; ?>">Back to Guides</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
	</section>
	<section id="primary" class="content-area">
		<main id="main" class="site-main container">

			<?php

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();	
				?>
			<article <?php post_class(); ?>>
				<div class="entry-content">    
					<div class="row">	
						<div id="guide-content" class="col-sm-9 no-padding guides">
							<?php
							$author1_id = get_post_meta(get_the_ID(),'guide_author1_id',true);
							if ($author1_id > 0) {
								$author1_bio = get_user_meta($author1_id, 'description', true);
								$author1_nickname = get_user_meta($author1_id, 'nickname', true);
								$author1_avatar = get_avatar_url($author1_id);
							}
							$author2_id = get_post_meta(get_the_ID(),'guide_author2_id',true);
							if ($author2_id > 0) {
								$author2_bio = get_user_meta($author2_id, 'description', true);
								$author2_nickname = get_user_meta($author2_id, 'nickname', true);
								$author2_avatar = get_avatar_url($author2_id);
							}
							$author3_id = get_post_meta(get_the_ID(),'guide_author3_id',true);
							if ($author3_id > 0) {
								$author3_bio = get_user_meta($author3_id, 'description', true);
								$author3_nickname = get_user_meta($author3_id, 'nickname', true);
								$author3_avatar = get_avatar_url($author3_id);
							}
							?>
							<h1 class="entry-title" style="color:#555;"><?php the_title(); ?></h1>
							<p>
								<?php 
								if ( $author1_id > 0 ) {
									echo 'By '; 
									echo '<a href="#authors">'.$author1_nickname.'</a>';
								}
								if ( $author2_id > 0 && $author3_id < 0 ) {
									echo ' and '.'<a href="#authors">'.$author2_nickname.'</a>';
								}
								if ( $author2_id > 0 && $author3_id > 0 ) {
									echo ', '.'<a href="#authors">'.$author2_nickname.'</a>'.', and '.'<a href="#authors">'.$author3_nickname.'</a>';
								}?></p>
							<?php the_content(); ?>
							<?php if ( $author1_id > 0): ?>
							<br />
							<hr style="border:solid 1px #ddd" />
							<br />
							<div class="panel panel-default" id="authors">
								<div class="panel-heading"><h2>Authors</strong></div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-2"><img src="<?php echo $author1_avatar; ?>" class="img-responsive img-circle" /></div>
										<div class="col-sm-10"><strong><?php echo $author1_nickname; ?></strong><p><?php echo $author1_bio; ?></p></div>
									</div>
									<?php if ( $author2_id > 0 ): ?>
									<hr />
									<div class="row">
										<div class="col-sm-2"><img src="<?php echo $author2_avatar; ?>" class="img-responsive img-circle" /></div>
										<div class="col-sm-10"><p class="lead"><?php echo $author2_nickname; ?></p><p><?php echo $author2_bio; ?></p></div>
									</div>
									<?php endif; ?>
									<?php if ( $author3_id > 0 ): ?>
									<hr />
									<div class="row">
										<div class="col-sm-2"><img src="<?php echo $author3_avatar; ?>" class="img-responsive img-circle" /></div>
										<div class="col-sm-10"><p class="lead"><?php echo $author3_nickname; ?></p><p><?php echo $author3_bio; ?></p></div>
									</div>
									<?php endif; ?>
								</div>
							</div>
							<br /><br /><br /><br /><br /><br />
							<?php endif; ?>
							<footer>
								<?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
							</footer>
						</div>
						<div class="col-sm-3">
							<div data-spy="affix" data-offset-top="60" data-offset-bottom="500">
								<?php get_guide_nav(); ?>
							</div>
						</div>
					</div>
				</div>
			</article>
			<?php

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="/wp-content/themes/blue-theme/assets/js/plugins/bootstrap/affix.js"></script>
<script src="/wp-content/themes/blue-theme/assets/js/plugins/bootstrap/scrollspy.js"></script>
<?php
get_footer();
