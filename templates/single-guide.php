<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <br />
      <span class="woocommerce"><?php woocommerce_breadcrumb(); ?></span>
    </header>
    <div class="entry-content">    	
    	<div id="guide-content" class="col-sm-9 no-padding">
          <h1 class="entry-title" style="color:#555;"><?php the_title(); ?></h1>
      		<?php the_content(); ?>
      		<footer>
		      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
		    </footer>
      	</div>
      	<div class="col-sm-3">
      		<div data-spy="affix" data-offset-top="120" data-offset-bottom="400" style="width:291px">
      			<?php get_guide_nav(); ?>
      		</div>
      	</div>
    </div>
  </article>
  <script type="text/javascript">
  	$(document).ready(function() {
  		$('body').scrollspy({ target: '.guidenav' });
  	});

    $(document).ready(function() {
      setTimeout(function() { 
        $('body').each(function () {
          var $spy = $(this).scrollspy('refresh')
        })
      }, 3000);
    })
  </script>
<?php endwhile; ?>