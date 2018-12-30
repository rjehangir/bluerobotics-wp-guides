<article <?php post_class(); ?>>
    <header>
      <br />
      <span class="woocommerce"><?php woocommerce_breadcrumb(); ?></span>
      <h1 class="entry-title">Tutorials and Guides</h1>
    </header>
    <div class="col-md-9 no-padding">
    	<div class="entry-content row">   
<?php while (have_posts()) : the_post(); ?>
	    	<div class="col-md-3">
	    		<a href="<?php echo esc_url( get_permalink() ); ?>">
		    		<?php the_post_thumbnail( 'shop_catalog', ['class' => 'img-responsive'] ); ?>
		    		<h3 class="product-title"><?php the_title(); ?></h3>
	    		</a>
	    		<span style="font-size:0.9em;color:#666;"><?php echo date('j F Y',strtotime(get_the_date())); ?></span>
	    		<p><?php the_excerpt(); ?></p>
	      	</div>
<?php endwhile; ?>
		</div>
	</div>
	<div class="col-md-3">
  		<div data-spy="affix" data-offset-top="170" style="width:291px">
  			<?php get_learn_archive_nav(); ?>
  		</div>
  	</div>
	<footer>
	  <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
	</footer>
</article>