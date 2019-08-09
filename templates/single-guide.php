<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <br />
      <span class="woocommerce"><nav class="woocommerce-breadcrumb">
        <a href="/">Home</a>&nbsp;/&nbsp;
        <a href="/learn">Guides</a>&nbsp;/&nbsp;
        <?php echo get_the_title(); ?>
      </nav></span>
    </header>
    <div class="entry-content">    	
    	<div id="guide-content" class="col-sm-9 no-padding">
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
            <div class="panel panel-default" id="authors">
              <div class="panel-heading"><strong>Authors</strong></div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-sm-2"><img src="<?php echo $author1_avatar; ?>" class="img-responsive img-circle" /></div>
                  <div class="col-sm-10"><p class="lead"><?php echo $author1_nickname; ?></p><p><?php echo $author1_bio; ?></p></div>
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
          <?php endif; ?>
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