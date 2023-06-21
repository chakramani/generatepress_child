<?php get_header() ?>

<?php if (have_posts()) { ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php if ($post->post_type == MAXGALLERIA_ALBUMS_POST_TYPE) { ?>
			<div class="mg-album-container">
				<h1 class="mg-album-title"><?php echo the_title() ?></h1>
				<?php echo do_shortcode('[maxalbum id="' . $post->ID . '"]') ?>
        <?php         
        if ( function_exists( 'sharing_display' ) ) {          
          echo sharing_display(); 
        }       
        ?>
			</div>
		<?php } ?>
	<?php endwhile; ?>
<?php } ?>

<?php get_footer() ?>