<?php 

/**
 * Product Related Slider By Category 
 */

function shortcode_callback_func_related_slider( $atts = array(), $content = '' ) {
	$atts = shortcode_atts( array(
		'id' => 'value',
	), $atts, 'shortcode-id' );

	 ob_start();

	  global $post;
	  // get categories
	  $terms = wp_get_post_terms( $post->ID, 'product_cat' );
	  foreach ( $terms as $term ) $cats_array[] = $term->term_id;
	  $query_args = array( 'post__not_in' => array( $post->ID ), 'posts_per_page' => 5, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'tax_query' => array( 
	    array(
	      'taxonomy' => 'product_cat',
	      'field' => 'id',
	      'terms' => $cats_array
	    )));
	  $r = new WP_Query($query_args);
			
	  if ($r->have_posts()) {
	    ?>
	    <div class="related-shop-area"><div id="relatedShop" class="owl-carousel owl-theme">

	      <?php while ($r->have_posts()) : $r->the_post(); global $product; ?>
	        <div class="single-related-shop-area">

	        	<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">

		        	<div class="single-related-image-area">
		        		<?php if (has_post_thumbnail()) 
		    			the_post_thumbnail(); ?>
		        	</div>

					<div class="single-related-title">
						<?php if ( get_the_title() ) the_title(); else the_ID(); ?>
					</div>
	    	
	        	</a> 
	        	
	        	<div class="single-related-price">
	        		<?php echo $product->get_price_html(); ?>
	        	</div>

	        </div>
	      <?php endwhile; ?>

	    </div></div>
	    <?php
	    // Reset the global $the_post as this query will have stomped on it
	    wp_reset_query();
	  }

	   return ob_get_clean();
	
}
add_shortcode( 'related_slider', 'shortcode_callback_func_related_slider' );