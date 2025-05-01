<?php 
/**
 * Default Sidebar
 */
return array(
	'title'      => esc_html__( 'Sidebar', 'disco-tailors-fse' ),
	'categories' => array( 'disco-tailors-fse', 'sidebar' ),
	'content'    => '<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50","padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"className":"SF-Sidebar","layout":{"type":"constrained"}} -->
<div class="wp-block-group SF-Sidebar" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"var:preset|spacing|50","bottom":"0","left":"var:preset|spacing|50"},"blockGap":"0","margin":{"bottom":"var:preset|spacing|60"}},"border":{"radius":"7px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="border-radius:7px;margin-bottom:var(--wp--preset--spacing--60);padding-top:0;padding-right:var(--wp--preset--spacing--50);padding-bottom:0;padding-left:var(--wp--preset--spacing--50)"><!-- wp:heading {"style":{"typography":{"fontSize":"30px","fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|50"}}}} -->
<h2 class="wp-block-heading" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--50);font-size:30px;font-style:normal;font-weight:600">Latest Post</h2>
<!-- /wp:heading -->

<!-- wp:latest-posts {"excerptLength":10,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageAlign":"left","featuredImageSizeWidth":70,"featuredImageSizeHeight":70,"addLinkToFeaturedImage":true,"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0px","left":"0"},"padding":{"right":"0","left":"0","top":"0","bottom":"0"}},"typography":{"lineHeight":"1.3","fontSize":"17px","fontStyle":"normal","fontWeight":"600"}},"className":"latest-post"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"var:preset|spacing|50","bottom":"0","left":"var:preset|spacing|50"},"margin":{"bottom":"var:preset|spacing|60"}},"border":{"radius":"7px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="border-radius:7px;margin-bottom:var(--wp--preset--spacing--60);padding-top:0;padding-right:var(--wp--preset--spacing--50);padding-bottom:0;padding-left:var(--wp--preset--spacing--50)"><!-- wp:heading {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}},"typography":{"fontSize":"30px","fontStyle":"normal","fontWeight":"600"}}} -->
<h2 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--50);font-size:30px;font-style:normal;font-weight:600">Categories</h2>
<!-- /wp:heading -->

<!-- wp:categories {"showPostCounts":true} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"var:preset|spacing|50","bottom":"0","left":"var:preset|spacing|50"},"margin":{"bottom":"var:preset|spacing|60"}},"border":{"radius":"7px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="border-radius:7px;margin-bottom:var(--wp--preset--spacing--60);padding-top:0;padding-right:var(--wp--preset--spacing--50);padding-bottom:0;padding-left:var(--wp--preset--spacing--50)"><!-- wp:heading {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}},"typography":{"fontSize":"30px","fontStyle":"normal","fontWeight":"600"}}} -->
<h2 class="wp-block-heading" style="margin-bottom:var(--wp--preset--spacing--50);font-size:30px;font-style:normal;font-weight:600">Archives</h2>
<!-- /wp:heading -->

<!-- wp:archives /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);
