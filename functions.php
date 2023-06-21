<?php

/**

 * GeneratePress child theme functions and definitions.

 *

 * Add your custom PHP in this file.

 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).

 */



/*

function mytheme_add_woocommerce_support() {

	add_theme_support( 'woocommerce' );

}

add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );  */

add_action('admin_enqueue_scripts', 'admin_child_theme_enqueue_styles');
function admin_child_theme_enqueue_styles()
{
	wp_enqueue_style('cpma-child-theme-admin-style', get_stylesheet_directory_uri() . '/admin_style.css');
}

add_action('wp_enqueue_script', 'child_theme_enqueue_styles');
function child_theme_enqueue_styles()
{
	wp_enqueue_style('cpm-child-theme-style-front', get_stylesheet_directory_uri() . '/style.css');
	wp_enqueue_script('cpm - jquery 3.3.1', "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js");
	wp_enqueue_script('cpm - owl-carousel2 2.3.4', "https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js");
}

add_action('admin_footer', 'gallery_post_type_style');
function gallery_post_type_style()
{
	$post_type = get_post_type();
	if ($post_type == 'gallery') {
?>
		<style>
			.interface-interface-skeleton__content {
				height: 130vh;
			}

			.acf-relationship .list {
				height: 500px;
			}
		</style>

	<?php
	}
}

add_action('wp_footer', 'product_frame_filter');
function product_frame_filter()
{
	?>

	<script>
		var myphotographer = localStorage.getItem('myphotographer');
		var p4d = localStorage.getItem('myp4d');
		var image_id = localStorage.getItem('cpm_image_id');
		var image_ratio = localStorage.getItem('cpm_image_ratio');
		image_ratio= image_ratio.replace('["', '').replace('"]', '');
		var image_ratios = image_ratio.split(",");
		var edition_ = jQuery('#p4d_title').text();
		for (let i = 0; i < image_ratios.length; i++) {
			if (image_ratios[i] == '- classical aspect ratio (3:4)' || image_ratios[i] == '- classical aspect ratio (3:2)') {
				jQuery('#Classic').attr('checked', true);
			} else if (image_ratios[i] == '- digital aspect ratio (4:3)') {
				jQuery('#Digital').attr('checked', true);
			} else if (image_ratios[i] == '- square aspect ratio (1:1)') {
				jQuery('#Square').attr('checked', true);
			}

		}

		if (edition_.search('LTD') > 0) {
			jQuery("#2cpm_p4d").attr('checked', true);
			edition = 'Limited Editions';
		} else if (edition_.search('UNL') > 0) {
			jQuery("#1cpm_p4d").attr('checked', true);
			edition = 'Unlimited Editions';
		}
		var currentPage = 1;
		jQuery.ajax({
			url: "<?php echo admin_url('admin-ajax.php'); ?>",
			type: "post",
			data: {
				action: "data_fetch_function",
				category: '',
				photo_type: '',
				edition: edition,
				photographer: myphotographer,
				p4d: p4d,
				image_id: image_id,
				image_ratio: image_ratio,
				paged: currentPage,
			},
			beforeSend: function() {},
			success: function(data) {
				jQuery("#products").html(data);
				jQuery("#load-more").show();

			},
			complete: function(data) {},
		});

		function select_child_from_parent_cat() {
			var parent_category = jQuery("#parent_category").find(":selected").val();
			if (parent_category) {
				jQuery.ajax({
					url: "<?php echo admin_url('admin-ajax.php'); ?>",
					type: "post",
					data: {
						action: "fetch_child_function",
						category: parent_category,
					},
					beforeSend: function() {},
					success: function(data) {
						jQuery("#child_category").html(data);

					},
					complete: function(data) {},
				});
			} else {
				jQuery.ajax({
					url: "<?php echo admin_url('admin-ajax.php'); ?>",
					type: "post",
					data: {
						action: "fetch_child_function",
						category: '',
					},
					beforeSend: function() {},
					success: function(data) {
						jQuery("#child_category").html(data);

					},
					complete: function(data) {},
				});
			}
		}


		function select_change_function() {
			var product_category = jQuery("#child_category input[name='child_category']:checked").val();
			var product_photo_type = jQuery(".aspect_ratio input[name='aspect_ratio']:checked").val();
			var edition_ratio = jQuery(".edition_ratio input[name='edition_ratio']:checked").val();
			let p4d_text = document.getElementById("p4d_title").innerHTML;
			// var p4d_text = jQuery("p4d_title").html();
			
			
			
			if (edition_ratio == 'Unlimited Editions') {
				document.getElementById("p4d_title").innerHTML = p4d_text.replace("LTD", "UNL");
			} else if (edition_ratio == 'Limited Editions') {
				document.getElementById("p4d_title").innerHTML = p4d_text.replace("UNL", "LTD");
			}
			
			let p4d_text1 = document.getElementById("p4d_title").innerHTML;
			if (product_photo_type == 'Classic') {
				document.getElementById("p4d_title").innerHTML = p4d_text1.replace("DIG", "CLA").replace("SQU", "CLA");
			} else if (product_photo_type == 'Digital') {
				document.getElementById("p4d_title").innerHTML = p4d_text1.replace("CLA", "DIG").replace("SQU", "DIG");
			} else if (product_photo_type == 'Square') {
				document.getElementById("p4d_title").innerHTML = p4d_text1.replace("CLA", "SQU").replace("DIG", "SQU");
			}

			// let p4d_text_title = document.getElementById("p4d_title").innerHTML;
			// let new_p4d_title = p4d_text_title.replace("P4D : ", "").replace("<script> ","").replace("document.write(p4d); ","").replace("<\/script>","");
			// localStorage.setItem('myp4d', new_p4d_title);
			// alert(new_p4d_title);
			
			if (product_category || product_photo_type || edition_ratio) {

				jQuery.ajax({
					url: "<?php echo admin_url('admin-ajax.php'); ?>",
					type: "post",
					data: {
						action: "data_fetch_function",
						category: product_category,
						photo_type: product_photo_type,
						edition: edition_ratio,
						photographer: myphotographer,
						p4d: p4d,
						image_id: image_id,
						image_ratio: image_ratio,
						paged: currentPage,
					},
					beforeSend: function() {},
					success: function(data) {
						jQuery("#products").html(data);
						jQuery("#load-more").show();

					},
					complete: function(data) {},
				})
			} else {
				jQuery.ajax({
					url: "<?php echo admin_url('admin-ajax.php'); ?>",
					type: "post",
					data: {
						action: "data_fetch_function",
						category: '',
						photo_type: '',
						edition: '',
						photographer: myphotographer,
						p4d: p4d,
						image_id: image_id,
						image_ratio: image_ratio,
						paged: currentPage,
					},
					beforeSend: function() {
						jQuery('.spinner-square').show();
					},
					success: function(data) {
						jQuery("#products").html(data);
						jQuery('.spinner-square').hide();
						jQuery("#load-more").show();

					},
					complete: function(data) {},
				});
			}
		}

		function reset_function() {
			jQuery("#select_category").val("").change();
			jQuery("#select_photo_type").val("").change();
		}

		function load_more_function() {
			currentPage++; // Do currentPage + 1, because we want to load the next page

			var product_category = jQuery("#child_category input[name='child_category']:checked").val();
			var product_photo_type = jQuery(".aspect_ratio input[name='aspect_ratio']:checked").val();
			var edition_ratio = jQuery(".edition_ratio input[name='edition_ratio']:checked").val();

			jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'POST',
				dataType: 'html',
				data: {
					action: 'data_fetch_function',
					paged: currentPage,
					category: product_category,
					photo_type: product_photo_type,
					edition: edition_ratio,
					photographer: myphotographer,
					p4d: p4d,
					image_id: image_id,
					image_ratio: image_ratio,
				},
				success: function(res) {
					jQuery('#products').append(res);
				}
			});
		}
		// });
	</script>
<?php
}

add_action('wp_ajax_data_fetch_function', 'data_fetch_function');
add_action('wp_ajax_nopriv_data_fetch_function', 'data_fetch_function');


function data_fetch_function()
{
	$ratio = '';
	$category = $_POST['category'];
	$photo_type = $_POST['photo_type'];
	$edition_ratio = $_POST['edition'];
	$paged = $_POST['paged'];
	$photo_grapher = $_POST['photographer'];
	$p4d = $_POST['p4d'];
	$image_id = (int)$_POST['image_id'];
	$image_title = get_the_title($image_id);
	$image_ratio = $_POST['image_ratio'];
	$phototypes = get_field('photo_type', $image_id);
	$image_ratios = explode(",", $image_ratio);
	foreach ($image_ratios as $image_r) {
		if ($image_r == '- classical aspect ratio (3:4)' || $image_r == '- classical aspect ratio (3:2)') {
			$ratio = 'Classic';
		} else if ($image_r == '- digital aspect ratio (4:3)') {
			$ratio = 'Digital';
		} else if ($image_r == '- square aspect ratio (1:1)') {
			$ratio = 'Square';
		}
	}

	if ($category != '' && $photo_type != '' && $edition_ratio != '') { // all product category, aspect ratio and edition ratio
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 20,
			'paged' => $paged,
			'tax_query' => [
				[
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' =>  $category,
				],
			],
			'meta_query'             => array(
				'relation' => 'AND',
				array(
					'key'     => 'aspect_ratio',
					'value'   => $photo_type,
					'compare' => 'LIKE',
				),
				array(
					'key'     => 'photo_editions',
					'value'   => 's:' . strlen($edition_ratio) . ':"' . $edition_ratio . '";',
					'compare' => 'LIKE',
				),
			),
		);
	} else if ($category != '' && $photo_type != '' && $edition_ratio == '') { //product category and aspect ratio

		$args = array(
			'post_type'        => array('product'),
			'post_status'     => 'publish',
			'posts_per_page'   => 20,
			'paged' => $paged,
			'tax_query' => [
				[
					'taxonomy' => 'product_cat',
					'terms' =>  $category,
				],
			],
			'meta_query'             => array(
				array(
					'key'     => 'aspect_ratio',
					'value'   => $photo_type,
					'compare' => 'LIKE',
				),
			),
		);
	} else if ($category != '' && $photo_type == '' && $edition_ratio != '') { //product category and edition

		$args = array(
			'post_type'        => array('product'),
			'post_status'     => 'publish',
			'posts_per_page'   => 20,
			'paged' => $paged,
			'tax_query' => [
				[
					'taxonomy' => 'product_cat',
					'terms' =>  $category,
				],
			],
			'meta_query'             => array(
				array(
					'key'     => 'photo_editions',
					'value' => 's:' . strlen($edition_ratio) . ':"' . $edition_ratio . '";',
					'compare' => 'LIKE',
				),
			),
		);
	} else if ($photo_type != '' && $edition_ratio != '' && $category == '') { // only Aspect ratio and edition ratio

		$args = array(
			'post_type'        => array('product'),
			'post_status'     => 'publish',
			'posts_per_page'   => 20,
			'paged' => $paged,
			'meta_query'             => array(
				'relation' => 'AND',
				array(
					'key'     => 'aspect_ratio',
					'value'   => $photo_type,
					'compare' => 'LIKE',
				),
				array(
					'key'     => 'photo_editions',
					'value'   => 's:' . strlen($edition_ratio) . ':"' . $edition_ratio . '";',
					'compare' => 'LIKE',
				),
			),
		);
	} else if ($photo_type == '' && $edition_ratio == '' && $category != '') { // only category

		$args = array(
			'post_type'        => array('product'),
			'post_status'     => 'publish',
			'posts_per_page'   => 20,
			'paged' => $paged,
			'tax_query' => [
				[
					'taxonomy' => 'product_cat',
					'terms' =>  $category,
				],
			],
		);
	} else if ($photo_type != '' && $edition_ratio == '' && $category == '') { // only photo type

		$args = array(
			'post_type'        => array('product'),
			'post_status'     => 'publish',
			'posts_per_page'   => 20,
			'paged' => $paged,
			'meta_query'             => array(
				array(
					'key'     => 'aspect_ratio',
					'value'   => $photo_type,
					'compare' => 'LIKE',
				),
			),
		);
	} else if ($photo_type == '' && $edition_ratio != '' && $category == '') { // only edition ratio
		$args = array(
			'post_type'        => array('product'),
			'post_status'     => 'publish',
			'posts_per_page'   => 20,
			'meta_query' => array(
				array(
					'key'     => 'photo_editions',
					'value'   => 's:' . strlen($edition_ratio) . ':"' . $edition_ratio . '";',
					'compare' => 'LIKE',
				),
			),
		);
	} else { // none
		$args = array(
			'post_type' => array('product'),
			'post_status'     => 'publish',
			'posts_per_page'  => 20,
			'paged' => $paged,
			'meta_query'             => array(
				'relation' => 'AND',
				array(
					'key'     => 'aspect_ratio',
					'value'   => $photo_type,
					'compare' => 'LIKE',
				),
				array(
					'key'     => 'photo_editions',
					'value'   => 's:' . strlen($edition_ratio) . ':"' . $edition_ratio . '";',
					'compare' => 'LIKE',
				),
			),
		);
	}
	$query = new WP_Query($args);
?>

	<div class="columns" id="shoppage">
		<?php
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		if (isset($_SESSION['myphotographer'])) {
			$theratio = $_SESSION['theratio'];
		}

		$base_url = home_url();
		$image_path = '/wp-content/uploads/';

		if ($query->have_posts()) :
			while ($query->have_posts()) : $query->the_post();
				$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');

		?>
				<div class="p4p_products">
					<div class="product_img">
						<?php if ($featured_img_url) { ?>
							<img src="<?php echo $featured_img_url; ?>" alt="img">
						<?php } else { ?>
							<img src="https://photos4deco.com/wp-content/uploads/woocommerce-placeholder-500x375.png" alt="img">
						<?php } ?>
					</div>
					<div class="product_desc">
						<div class="product_size">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</div>
						<div class="product_add_to_cart">
							<?php echo do_shortcode('[add_to_cart id="' . get_the_ID() . '"]'); ?>
						</div>
					</div>
				</div>
			<?php
			endwhile; ?>

		<?php
		else : ?>
			<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
		<?php endif;
		wp_reset_query();
		?>
	</div>

	<?php
	die();
}


add_action('wp_ajax_fetch_child_function', 'fetch_child_function');
add_action('wp_ajax_nopriv_fetch_child_function', 'fetch_child_function');

function fetch_child_function()
{
	$parent_category = $_POST['category'];
	if ($parent_category != '') {

		$terms = get_terms(array(
			'taxonomy'   => 'product_cat',
			'parent' => $parent_category
		));

		foreach ($terms as $key => $term) { ?>
			<input type="radio" id="child_category" name="child_category" value="<?php echo $terms[$key]->term_id; ?>" onclick="select_change_function();">
			<label for="html"><?php echo $terms[$key]->name; ?></label>
<?php }
	} else {
		die();
	}
	die();
}
