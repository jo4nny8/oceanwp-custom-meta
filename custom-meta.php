<?php
/**
 * The default template for displaying post meta.
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get meta sections
$sections = oceanwp_blog_entry_meta();

// Return if sections are empty
if ( empty( $sections ) ) {
	return;
} ?>

<?php do_action( 'ocean_before_blog_entry_meta' ); ?>

<ul class="meta clr">

	<?php
	// Loop through meta sections
	foreach ( $sections as $section ) { ?>

		<?php if ( 'author' == $section ) { ?>
			<li class="meta-author"<?php oceanwp_schema_markup( 'author_name' ); ?>><i class="icon-user"></i><?php echo the_author_posts_link(); ?></li>
		<?php } ?>

		<?php if ( 'date' == $section ) { ?>
			<li class="meta-date"<?php oceanwp_schema_markup( 'publish_date' ); ?>><i class="icon-clock"></i><?php echo get_the_date(); ?></li>
		<?php } ?>

		<?php if ( 'categories' == $section ) { ?>
			
			<?php
			$terms = get_the_terms($post->ID, 'installation-categories' );
			if ($terms && ! is_wp_error($terms)) :
			    $term_slugs_arr = array();
			      foreach ($terms as $term) {
				$term_slugs_arr[] = $term->slug;
			      }
			$terms_slug_str = join( " ", $term_slugs_arr);           
                  ?>
                  <li class="meta-cat">
                  <i class="icon-folder"></i>
          		      <?php echo '<span ><a href="/installation-categories/'.$term->slug.'/">'.$term->name.'</a></span>';?>
          				</li>
          		<?php endif;?>


				<li class="meta-location">
          <i class="icon-map"></i>
          <span><?php echo types_render_field( "location", array( ) );?></span>
        </li>
				
				<?php

        if (is_singular ('fitted-bathrooms') || is_singular('fitted-kitchens') || is_singular('fitted-bedrooms')) {
          continue;
        }

        else {
            // Find connected pages
            $child_args = array(
              'post_type' => 'staff-members',
              'posts_per_page' => 1,
              'order' => 'ASC',
              'toolset_relationships' => array(
                  'role' => 'child',
                  'related_to' => get_the_ID(),
                  'relationship' => 'installation-to-staff-member'
              )
          );
     
              $connected = new WP_Query( $child_args );

              // Display connected pages
              if ( $connected->have_posts() ) :
                ?>
                <li class="meta-fitter">
                  <i class="icon-user"></i>
                    <?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
                    <span><a href="<?php the_permalink();?>"><?php the_title();?></a></span>
                    <?php endwhile; ?>
                    <?php
                    // Prevent weirdness
                    wp_reset_postdata();

                  endif;
                ?>
                </li>
				<?php
      }

			
		  } ?>

		<?php if ( 'comments' == $section && comments_open() && ! post_password_required() ) { ?>
			<li class="meta-comments"><i class="icon-bubble"></i><?php comments_popup_link( esc_html__( '0 Comments', 'oceanwp' ), esc_html__( '1 Comment',  'oceanwp' ), esc_html__( '% Comments', 'oceanwp' ), 'comments-link' ); ?></li>
		<?php } ?>

	<?php } ?>
	
</ul>

<?php do_action( 'ocean_after_blog_entry_meta' ); ?>
