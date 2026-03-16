<?php
/**
 * Title: Front Page
 * Slug: horizon-blocks/front-page
 * Categories: horizon-blocks-pages
 * Inserter: false
 *
 * @package HorizonBlocks
 */
?>
<!-- wp:group {"tagName":"main","layout":{"type":"default"},"style":{"spacing":{"blockGap":"0"}}} -->
<main class="wp-block-group">
	<!-- wp:cover {"dimRatio":0,"minHeight":82,"minHeightUnit":"vh","isDark":false,"align":"full","style":{"spacing":{"padding":{"top":"4rem","right":"1.5rem","bottom":"4rem","left":"1.5rem"}}}} -->
	<div class="wp-block-cover alignfull is-light" style="padding-top:4rem;padding-right:1.5rem;padding-bottom:4rem;padding-left:1.5rem;min-height:82vh">
		<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
		<div class="wp-block-cover__inner-container">
			<!-- wp:columns {"align":"wide","verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"4rem"}}}} -->
			<div class="wp-block-columns alignwide are-vertically-aligned-center">
				<!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
				<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
					<!-- wp:paragraph {"textColor":"secondary","fontSize":"small"} -->
					<p class="has-secondary-color has-text-color has-small-font-size">Modern WordPress theme</p>
					<!-- /wp:paragraph -->
					<!-- wp:heading {"level":1,"fontSize":"x-large"} -->
					<h1 class="wp-block-heading has-x-large-font-size">Build a polished site from reusable blocks and template parts.</h1>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"fontSize":"medium"} -->
					<p class="has-medium-font-size">This front page is driven by a block pattern, so you can replace sections, edit content, and rearrange the layout directly in the Site Editor.</p>
					<!-- /wp:paragraph -->
					<!-- wp:buttons -->
					<div class="wp-block-buttons">
						<!-- wp:button {"backgroundColor":"primary","textColor":"base","url":"#start"} -->
						<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-primary-background-color has-text-color has-background wp-element-button" href="#start">Start editing</a></div>
						<!-- /wp:button -->
						<!-- wp:button {"className":"is-style-outline","url":"#services"} -->
						<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#services">View services</a></div>
						<!-- /wp:button -->
					</div>
					<!-- /wp:buttons -->
				</div>
				<!-- /wp:column -->
				<!-- wp:column {"verticalAlignment":"center","width":"42%"} -->
				<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:42%">
					<!-- wp:group {"backgroundColor":"surface","style":{"border":{"radius":"28px"},"spacing":{"padding":{"top":"2.5rem","right":"2.5rem","bottom":"2.5rem","left":"2.5rem"},"blockGap":"1.5rem"}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group has-surface-background-color has-background" style="border-radius:28px;padding-top:2.5rem;padding-right:2.5rem;padding-bottom:2.5rem;padding-left:2.5rem">
						<!-- wp:paragraph {"textColor":"muted","fontSize":"small"} -->
						<p class="has-muted-color has-text-color has-small-font-size">Launch stack</p>
						<!-- /wp:paragraph -->
						<!-- wp:list -->
						<ul>
							<li>Reusable header and footer template parts</li>
							<li>Templates for pages, posts, archives, search, and 404</li>
							<li>npm-based Sass and JavaScript build scripts</li>
						</ul>
						<!-- /wp:list -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:column -->
			</div>
			<!-- /wp:columns -->
		</div>
	</div>
	<!-- /wp:cover -->

	<!-- wp:pattern {"slug":"horizon-blocks/feature-grid"} /-->

	<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem","left":"1.5rem","right":"1.5rem"}},"color":{"background":"#18212a","text":"#f7f4ee"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-text-color has-background" style="background-color:#18212a;color:#f7f4ee;padding-top:4rem;padding-right:1.5rem;padding-bottom:4rem;padding-left:1.5rem">
		<!-- wp:group {"align":"wide","layout":{"type":"constrained"},"style":{"spacing":{"blockGap":"0.75rem"}}} -->
		<div class="wp-block-group alignwide">
			<!-- wp:paragraph {"textColor":"secondary","fontSize":"small"} -->
			<p class="has-secondary-color has-text-color has-small-font-size">Latest writing</p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"fontSize":"large"} -->
			<h2 class="wp-block-heading has-large-font-size">Recent posts and updates</h2>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->
		<!-- wp:query {"query":{"perPage":3,"postType":"post","order":"desc","orderBy":"date","inherit":false},"align":"wide","layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide">
			<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
				<!-- wp:group {"backgroundColor":"surface","textColor":"contrast","style":{"spacing":{"blockGap":"0.75rem","padding":{"top":"1.5rem","right":"1.5rem","bottom":"1.5rem","left":"1.5rem"}},"border":{"radius":"22px"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group has-contrast-color has-surface-background-color has-text-color has-background" style="border-radius:22px;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
					<!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->
					<!-- wp:post-excerpt {"excerptLength":20,"moreText":"Read more"} /-->
				</div>
				<!-- /wp:group -->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->
	</div>
	<!-- /wp:group -->
</main>
<!-- /wp:group -->
