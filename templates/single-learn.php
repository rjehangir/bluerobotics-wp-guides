<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <br />
      <span class="woocommerce"><?php woocommerce_breadcrumb(); ?></span>
      <h1 class="entry-title" style="color:#555;"><?php the_title(); ?></h1>
    </header>
    <div class="entry-content">    	
    	<div id="learn-content" class="col-md-9 no-padding">
      		<?php the_content(); ?>
      		<footer>
		      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
		    </footer>
      	</div>
      	<div class="col-md-3">
      		<div data-spy="affix" data-offset-top="170" style="width:291px">
      			<?php get_learn_nav(); ?>
      		</div>
      	</div>
    </div>
  </article>
  <script type="text/javascript">
  	$(document).ready(function() {
  		$('body').scrollspy({ target: '.learnnav' });
  	});
  </script>
<?php endwhile; ?>