<?php
/* Article: inner-page hero (title, meta, share | framed image), sticky "On this page" sidebar | body, navy CTA band. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$content = apply_filters( 'the_content', get_the_content() );
	list( $content, $toc ) = cga_headings( $content );
	$cats    = get_the_category();
	$cat     = $cats ? $cats[0] : null;
	$blog    = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' );
	$url     = rawurlencode( get_permalink() );
	$title   = rawurlencode( wp_strip_all_tags( get_the_title() ) );
	$mins    = cga_reading_minutes( $content );
	$share   = array(
		'Facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		'LinkedIn' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
		'WhatsApp' => 'https://wa.me/?text=' . $title . '%20' . $url,
		'Email'    => 'mailto:?subject=' . $title . '&body=' . $url,
	);
	$icons = array(
		'Facebook' => '<path d="M14 8h3V4h-3c-2.8 0-4 1.7-4 4.3V10H8v4h2v8h4v-8h3l1-4h-4V8.6c0-.4.3-.6.6-.6Z"/>',
		'LinkedIn' => '<path d="M6.5 8.5H3V21h3.5V8.5ZM4.8 3a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM21 14c0-3.4-2-5.7-5-5.7-1.6 0-2.7.8-3.2 1.6V8.5H9.4V21h3.5v-6.3c0-1.7.6-3 2.3-3 1.6 0 2.3 1.2 2.3 3V21H21v-7Z"/>',
		'WhatsApp' => '<path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm5.5 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7-2.8-1.1-4.6-4-4.7-4.2-.1-.2-1.1-1.5-1.1-2.9 0-1.4.7-2 1-2.3.3-.3.6-.4.8-.4h.6c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .6l-.4.6-.4.5c-.1.1-.3.3-.1.6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.4 1.5.3.1.5.1.6-.1l.9-1c.2-.3.4-.2.7-.1l2 1c.3.1.5.2.6.3.1.2.1.7-.1 1.3Z"/>',
		'Email'    => '<path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm9 7.4L4 7.2V17h16V7.2l-8 5.2ZM5.4 7l6.6 4.3L18.6 7H5.4Z"/>',
	);
	?>
<div id="cga" class="cga cga-single">
	<section class="cga-hero" aria-labelledby="cga-h1">
		<div class="cga-hero-in">
			<div class="cga-hero-txt">
				<nav class="cga-bc" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span aria-hidden="true">›</span><a href="<?php echo esc_url( $blog ); ?>">Blog</a><?php if ( $cat && ! in_array( $cat->slug, array( 'blog', 'uncategorized' ), true ) ) : ?><span aria-hidden="true">›</span><a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a><?php endif; ?></nav>
				<p class="cga-eb"><?php echo esc_html( $cat && ! in_array( $cat->slug, array( 'blog', 'uncategorized' ), true ) ? $cat->name : 'Blog' ); ?></p>
				<h1 id="cga-h1" class="cga-h1"><?php the_title(); ?></h1>
				<p class="cga-meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time><span aria-hidden="true">·</span><?php echo esc_html( $mins . ' min read' ); ?></p>
				<div class="cga-share" aria-label="Share">
					<span>Share</span>
					<?php foreach ( $share as $name => $href ) : ?>
					<a href="<?php echo esc_url( $href ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( 'Share on ' . $name ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><?php echo $icons[ $name ]; // Static SVG. ?></svg></a>
					<?php endforeach; ?>
				</div>
			</div>
			<?php if ( has_post_thumbnail() ) : ?>
			<figure class="cga-hero-vis"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></figure>
			<?php endif; ?>
		</div>
	</section>

	<div class="cga-grid">
		<aside class="cga-side" aria-label="On this page">
			<div class="cga-side-in">
				<a class="cga-back" href="<?php echo esc_url( $blog ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>All articles</a>
				<?php if ( $toc ) : ?>
				<p class="cga-side-t">On this page</p>
				<nav class="cga-toc">
					<ol>
						<?php foreach ( $toc as $h ) : ?>
						<li class="cga-<?php echo esc_attr( $h['level'] ); ?>"><a href="#<?php echo esc_attr( $h['id'] ); ?>"><?php echo esc_html( $h['text'] ); ?></a></li>
						<?php endforeach; ?>
					</ol>
				</nav>
				<?php endif; ?>
			</div>
		</aside>

		<article class="cga-main">
			<?php if ( $toc ) : ?>
			<details class="cga-toc-m">
				<summary>On this page</summary>
				<ol>
					<?php foreach ( $toc as $h ) : ?>
					<li class="cga-<?php echo esc_attr( $h['level'] ); ?>"><a href="#<?php echo esc_attr( $h['id'] ); ?>"><?php echo esc_html( $h['text'] ); ?></a></li>
					<?php endforeach; ?>
				</ol>
			</details>
			<?php endif; ?>

			<div class="cga-body">
				<?php echo $content; // Post content, already filtered by WordPress. ?>
			</div>
		</article>
	</div>

	<?php cga_cta_band(); ?>
</div>
	<?php
endwhile;
get_footer();
