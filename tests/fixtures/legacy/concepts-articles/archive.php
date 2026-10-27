<?php
/* Blog listing (/blog/ and category pages): inner-page hero, card grid with page numbers, navy CTA band. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$is_cat = is_category();
$paged  = max( 1, (int) get_query_var( 'paged' ) );
$blog   = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' );
$latest = get_posts( array( 'numberposts' => 1, 'post_status' => 'publish', 'cat' => $is_cat ? get_queried_object_id() : 0 ) );
$latest = $latest ? $latest[0] : null;
?>
<div id="cga" class="cga cga-list">

	<section class="cga-hero" aria-labelledby="cga-h1">
		<div class="cga-hero-in">
			<div class="cga-hero-txt">
				<nav class="cga-bc" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">›</span><?php if ( $is_cat ) : ?><a href="<?php echo esc_url( $blog ); ?>">Blog</a><span aria-hidden="true">›</span><span aria-current="page"><?php echo esc_html( single_cat_title( '', false ) ); ?></span><?php else : ?><span aria-current="page">Blog</span><?php endif; ?></nav>
				<p class="cga-eb"><?php echo $is_cat ? 'Category' : 'Blog'; ?></p>
				<h1 id="cga-h1" class="cga-h1"><?php echo $is_cat ? esc_html( single_cat_title( '', false ) ) : cga_accent( 'Resources for growing businesses', 'growing businesses' ); // Escaped. ?></h1>
				<p class="cga-lead"><?php echo esc_html( $is_cat && category_description() ? wp_strip_all_tags( category_description() ) : 'Guides on hiring, housing and managing a workforce in Malaysia, from the Concepts Group team.' ); ?></p>
			</div>
			<?php if ( $latest && has_post_thumbnail( $latest ) ) : ?>
			<a class="cga-hero-vis" href="<?php echo esc_url( get_permalink( $latest ) ); ?>">
				<?php echo get_the_post_thumbnail( $latest, 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => '' ) ); ?>
				<span class="cga-latest"><small>Latest article</small><b><?php echo esc_html( get_the_title( $latest ) ); ?></b></span>
			</a>
			<?php endif; ?>
		</div>
	</section>

	<section class="cga-grid-sec" aria-label="<?php echo esc_attr( 'Articles' . ( $paged > 1 ? ', page ' . $paged : '' ) ); ?>">
		<?php if ( have_posts() ) : ?>
		<div class="cgl-grid">
			<?php while ( have_posts() ) : the_post();
				$cats = array_values( array_filter( get_the_category(), function ( $c ) { return 'blog' !== $c->slug && 'uncategorized' !== $c->slug; } ) );
				$mins = cga_reading_minutes( get_post_field( 'post_content', get_the_ID() ) );
				$link = get_permalink(); ?>
			<article class="cgl-card">
				<a class="cgl-ph" href="<?php echo esc_url( $link ); ?>" tabindex="-1" aria-hidden="true">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy', 'alt' => '' ) ); } ?>
				</a>
				<div class="cgl-body">
					<p class="cgl-meta">
						<?php if ( $cats ) : ?><span class="cgl-tag"><?php echo esc_html( $cats[0]->name ); ?></span><?php else : ?><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></time><?php endif; ?>
						<span aria-hidden="true">·</span><span><?php echo esc_html( $mins . ' min read' ); ?></span>
					</p>
					<h2 class="cgl-t"><a href="<?php echo esc_url( $link ); ?>"><?php the_title(); ?></a></h2>
					<a class="cgl-more" href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( 'Read more: ' . wp_strip_all_tags( get_the_title() ) ); ?>">Read more</a>
				</div>
			</article>
			<?php endwhile; ?>
		</div>

		<?php
		$pages = paginate_links( array(
			'type'      => 'array',
			'mid_size'  => 1,
			'prev_text' => '<span class="cga-sr">Previous page</span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'next_text' => '<span class="cga-sr">Next page</span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		) );
		if ( $pages ) : ?>
		<nav class="cgl-pages" aria-label="Pages">
			<?php foreach ( $pages as $p ) { echo $p; } // Built by WordPress. ?>
		</nav>
		<?php endif; ?>
		<?php else : ?>
		<p class="cgl-empty">No articles yet.</p>
		<?php endif; ?>
	</section>

	<?php cga_cta_band(); ?>
</div>
<?php
get_footer();
