<?php
/**
 * Theme SEO helpers.
 *
 * @package HorizonBlocks
 */

if ( ! function_exists( 'horizon_blocks_has_seo_plugin' ) ) {
	/**
	 * Detects common SEO plugins to avoid duplicate metadata.
	 */
	function horizon_blocks_has_seo_plugin(): bool {
		return defined( 'WPSEO_VERSION' )
			|| defined( 'RANK_MATH_VERSION' )
			|| defined( 'SEOPRESS_VERSION' )
			|| defined( 'AIOSEO_VERSION' )
			|| class_exists( 'The_SEO_Framework\\Load' );
	}
}

if ( ! function_exists( 'horizon_blocks_get_current_url' ) ) {
	/**
	 * Builds the current request URL.
	 */
	function horizon_blocks_get_current_url(): string {
		if ( is_singular() ) {
			$canonical = wp_get_canonical_url();

			if ( $canonical ) {
				return $canonical;
			}
		}

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';

		if ( $request_uri ) {
			return home_url( $request_uri );
		}

		return home_url( '/' );
	}
}

if ( ! function_exists( 'horizon_blocks_trim_text' ) ) {
	/**
	 * Produces a plain-text SEO-safe excerpt.
	 *
	 * @param string $text Source text.
	 * @param int    $length Word limit.
	 */
	function horizon_blocks_trim_text( string $text, int $length = 28 ): string {
		$text = wp_strip_all_tags( $text );
		$text = preg_replace( '/\s+/', ' ', $text );
		$text = trim( (string) $text );

		if ( '' === $text ) {
			return '';
		}

		return wp_trim_words( $text, $length, '...' );
	}
}

if ( ! function_exists( 'horizon_blocks_get_meta_description' ) ) {
	/**
	 * Returns the best description for the current request.
	 */
	function horizon_blocks_get_meta_description(): string {
		if ( is_front_page() || is_home() ) {
			$front_page_id = (int) get_option( 'page_on_front' );

			if ( $front_page_id > 0 ) {
				$excerpt = get_post_field( 'post_excerpt', $front_page_id );
				$content = get_post_field( 'post_content', $front_page_id );
				$text    = horizon_blocks_trim_text( $excerpt ?: $content );

				if ( $text ) {
					return $text;
				}
			}

			return horizon_blocks_trim_text( get_bloginfo( 'description', 'display' ), 24 );
		}

		if ( is_singular() ) {
			$post = get_queried_object();

			if ( $post instanceof WP_Post ) {
				$text = horizon_blocks_trim_text( has_excerpt( $post->ID ) ? $post->post_excerpt : $post->post_content );

				if ( $text ) {
					return $text;
				}
			}
		}

		if ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();

			if ( isset( $term->description ) ) {
				$text = horizon_blocks_trim_text( $term->description );

				if ( $text ) {
					return $text;
				}
			}
		}

		if ( is_author() ) {
			$author = get_queried_object();

			if ( isset( $author->description ) ) {
				$text = horizon_blocks_trim_text( $author->description );

				if ( $text ) {
					return $text;
				}
			}
		}

		if ( is_search() ) {
			return sprintf(
				/* translators: %s: search query. */
				__( 'Search results for %s on %s.', 'horizon-blocks' ),
				get_search_query(),
				get_bloginfo( 'name' )
			);
		}

		if ( is_archive() ) {
			$description = horizon_blocks_trim_text( get_the_archive_description() );

			if ( $description ) {
				return $description;
			}
		}

		if ( is_404() ) {
			return sprintf(
				/* translators: %s: site name. */
				__( 'The requested page could not be found on %s.', 'horizon-blocks' ),
				get_bloginfo( 'name' )
			);
		}

		return horizon_blocks_trim_text( get_bloginfo( 'description', 'display' ), 24 );
	}
}

if ( ! function_exists( 'horizon_blocks_get_meta_image' ) ) {
	/**
	 * Returns the best share image for the current request.
	 */
	function horizon_blocks_get_meta_image(): string {
		$options = function_exists( 'horizon_blocks_get_theme_options' ) ? horizon_blocks_get_theme_options() : array();

		if ( is_singular() && has_post_thumbnail() ) {
			$image = get_the_post_thumbnail_url( get_queried_object_id(), 'full' );

			if ( $image ) {
				return $image;
			}
		}

		$site_icon_id = (int) get_option( 'site_icon' );

		if ( $site_icon_id > 0 ) {
			$image = wp_get_attachment_image_url( $site_icon_id, 'full' );

			if ( $image ) {
				return $image;
			}
		}

		$custom_logo_id = (int) get_theme_mod( 'custom_logo' );

		if ( $custom_logo_id > 0 ) {
			$image = wp_get_attachment_image_url( $custom_logo_id, 'full' );

			if ( $image ) {
				return $image;
			}
		}

		if ( ! empty( $options['default_social_image'] ) ) {
			return (string) $options['default_social_image'];
		}

		return '';
	}
}

if ( ! function_exists( 'horizon_blocks_get_schema_graph' ) ) {
	/**
	 * Builds a JSON-LD graph for the current request.
	 */
	function horizon_blocks_get_schema_graph(): array {
		$options      = function_exists( 'horizon_blocks_get_theme_options' ) ? horizon_blocks_get_theme_options() : array();
		$site_name    = get_bloginfo( 'name' );
		$site_url     = home_url( '/' );
		$current_url  = horizon_blocks_get_current_url();
		$description  = horizon_blocks_get_meta_description();
		$image        = horizon_blocks_get_meta_image();
		$graph        = array();
		$organization = array(
			'@type' => 'Organization',
			'@id'   => trailingslashit( $site_url ) . '#organization',
			'name'  => ! empty( $options['organization_name'] ) ? $options['organization_name'] : $site_name,
			'url'   => $site_url,
		);

		if ( ! empty( $options['contact_email'] ) ) {
			$organization['email'] = $options['contact_email'];
		}

		if ( ! empty( $options['contact_phone'] ) ) {
			$organization['telephone'] = $options['contact_phone'];
		}

		if ( $image ) {
			$organization['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => $image,
			);
		}

		$graph[] = $organization;

		$website = array(
			'@type'           => 'WebSite',
			'@id'             => trailingslashit( $site_url ) . '#website',
			'url'             => $site_url,
			'name'            => $site_name,
			'description'     => get_bloginfo( 'description', 'display' ),
			'publisher'       => array( '@id' => trailingslashit( $site_url ) . '#organization' ),
			'inLanguage'      => get_bloginfo( 'language' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);

		$graph[] = $website;

		$webpage = array(
			'@type'        => is_search() ? 'SearchResultsPage' : ( is_front_page() ? 'CollectionPage' : 'WebPage' ),
			'@id'          => trailingslashit( $current_url ) . '#webpage',
			'url'          => $current_url,
			'name'         => wp_get_document_title(),
			'description'  => $description,
			'isPartOf'     => array( '@id' => trailingslashit( $site_url ) . '#website' ),
			'inLanguage'   => get_bloginfo( 'language' ),
			'dateModified' => get_lastpostmodified( DATE_W3C ),
		);

		if ( $image ) {
			$webpage['primaryImageOfPage'] = array(
				'@type' => 'ImageObject',
				'url'   => $image,
			);
		}

		$graph[] = $webpage;

		if ( is_singular( 'post' ) ) {
			$post = get_queried_object();

			if ( $post instanceof WP_Post ) {
				$article = array(
					'@type'            => 'BlogPosting',
					'headline'         => get_the_title( $post ),
					'datePublished'    => get_post_time( DATE_W3C, true, $post ),
					'dateModified'     => get_post_modified_time( DATE_W3C, true, $post ),
					'mainEntityOfPage' => array( '@id' => trailingslashit( $current_url ) . '#webpage' ),
					'author'           => array(
						'@type' => 'Person',
						'name'  => get_the_author_meta( 'display_name', (int) $post->post_author ),
					),
					'publisher'        => array( '@id' => trailingslashit( $site_url ) . '#organization' ),
					'description'      => $description,
				);

				if ( $image ) {
					$article['image'] = array( $image );
				}

				$graph[] = $article;
			}
		}

		return array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		);
	}
}

if ( ! function_exists( 'horizon_blocks_print_meta_tags' ) ) {
	/**
	 * Outputs canonical, social, and schema metadata.
	 */
	function horizon_blocks_print_meta_tags(): void {
		if ( is_admin() || horizon_blocks_has_seo_plugin() ) {
			return;
		}

		$title       = wp_get_document_title();
		$description = horizon_blocks_get_meta_description();
		$current_url = horizon_blocks_get_current_url();
		$image       = horizon_blocks_get_meta_image();
		$type        = is_singular( 'post' ) ? 'article' : 'website';

		if ( $description ) {
			printf( "<meta name=\"description\" content=\"%s\" />\n", esc_attr( $description ) );
		}

		if ( ! is_404() ) {
			printf( "<link rel=\"canonical\" href=\"%s\" />\n", esc_url( $current_url ) );
		}
		printf( "<meta property=\"og:locale\" content=\"%s\" />\n", esc_attr( str_replace( '-', '_', get_bloginfo( 'language' ) ) ) );
		printf( "<meta property=\"og:site_name\" content=\"%s\" />\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( "<meta property=\"og:type\" content=\"%s\" />\n", esc_attr( $type ) );
		printf( "<meta property=\"og:title\" content=\"%s\" />\n", esc_attr( $title ) );
		printf( "<meta property=\"og:url\" content=\"%s\" />\n", esc_url( $current_url ) );

		if ( $description ) {
			printf( "<meta property=\"og:description\" content=\"%s\" />\n", esc_attr( $description ) );
			printf( "<meta name=\"twitter:description\" content=\"%s\" />\n", esc_attr( $description ) );
		}

		printf( "<meta name=\"twitter:card\" content=\"%s\" />\n", esc_attr( $image ? 'summary_large_image' : 'summary' ) );
		printf( "<meta name=\"twitter:title\" content=\"%s\" />\n", esc_attr( $title ) );

		if ( $image ) {
			printf( "<meta property=\"og:image\" content=\"%s\" />\n", esc_url( $image ) );
			printf( "<meta name=\"twitter:image\" content=\"%s\" />\n", esc_url( $image ) );
		}

		printf(
			"<script type=\"application/ld+json\">%s</script>\n",
			wp_json_encode( horizon_blocks_get_schema_graph(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		);
	}
}

add_action( 'wp_head', 'horizon_blocks_print_meta_tags', 1 );

if ( ! function_exists( 'horizon_blocks_filter_wp_robots' ) ) {
	/**
	 * Applies better crawl directives for low-value pages.
	 *
	 * @param array<string, bool> $robots Existing robots directives.
	 * @return array<string, bool>
	 */
	function horizon_blocks_filter_wp_robots( array $robots ): array {
		if ( horizon_blocks_has_seo_plugin() ) {
			return $robots;
		}

		if ( is_search() || is_404() ) {
			$robots['noindex']  = true;
			$robots['nofollow'] = false;
		}

		return $robots;
	}
}

add_filter( 'wp_robots', 'horizon_blocks_filter_wp_robots' );
