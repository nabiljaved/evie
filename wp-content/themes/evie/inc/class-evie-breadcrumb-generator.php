<?php
/**
 * Generates and displays Breadcrumb navigation.
 *
 * @package Evie
 * @version 1.0.0
 */

/**
 * Evie Breadcrumb Generator class.
 */
class Evie_Breadcrumb_Generator {

	/**
	 * A single instance of the class.
	 *
	 * @var Evie_Breadcrumb_Generator Instance of this class
	 */
	public static $instance;

	/**
	 * Blog's show on front setting, 'page' or 'posts'.
	 *
	 * @var string Blog's show on front setting, 'page' or 'posts'
	 */
	private $show_on_front;

	/**
	 * Current post object.
	 *
	 * @var mixed Current post object
	 */
	private $post;

	/**
	 * Array of the options.
	 *
	 * @var array Array of the options.
	 */
	private $options;

	/**
	 * Breadcrumb separator.
	 *
	 * @var string Breadcrumb separator
	 */
	private $separator = '/';

	/**
	 * HTML wrapper for the breadcrumbs output.
	 *
	 * @var string HTML wrapper for the breadcrumbs output.
	 */
	private $wrapper = 'div';

	/**
	 * An array object of the crumbs.
	 *
	 * @var array Array object of the crumbs.
	 *
	 * Each element of the crumbs array can either have one of these keys:
	 *    "id"         for post types;
	 *    "ptarchive"  for a post type archive;
	 *    "term"       for a taxonomy term.
	 * OR it consists of a predefined set of 'text', 'url'
	 */
	private $crumbs = array();

	/**
	 * Number of the elements in the breadcrumb.
	 *
	 * @var array Count of the elements in the $crumbs property
	 */
	private $crumb_count = 0;

	/**
	 * An array of the individual (linked) html strings created from crumbs.
	 *
	 * @var array Array of individual (linked) html strings.
	 */
	private $links = array();

	/**
	 * An array of the JSON-LD.
	 *
	 * @var array Array of the JSON-LD.
	 */
	private $json_data = array();

	/**
	 * Breadcrumb html string.
	 *
	 * @var string Breadcrumb html string
	 */
	private $output;

	/**
	 * Initializes and creates the breadcrumb.
	 *
	 * @param array $options The options for the breadcrumb.
	 */
	public function __construct( $options = array() ) {

		$defaults = array(
			'home'            => esc_html__( 'Home', 'evie' ),
			'home_url'        => home_url(),
			'archive_prefix'  => '',
			'search_prefix'   => esc_html__( 'Search Results for', 'evie' ),
			'404_prefix'      => esc_html__( 'Page not found', 'evie' ),
			'separator'       => '/',
			'taxonomy'        => 'category',
			'allow_duplicate' => true,
			'rich_snippet'    => true,
			'class'           => 'evie-breadcrumb',
		);

		$this->options = wp_parse_args( $options, $defaults );

		/**
		 * Filters the breadcrumb options.
		 *
		 * @param array $options Array of breadcrumb options.
		 */
		$this->options = apply_filters( 'evie_breadcrumb_options', $this->options );

		$this->separator = '<span class="breadcrumb-sep">' . $this->options['separator'] . '</span>';

		$this->post = ( isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null );

		$this->show_on_front = get_option( 'show_on_front' );

		$this->add_crumbs();

	}

	/**
	 * Returns a Homepage crumb.
	 *
	 * @return array A home crumb object.
	 */
	private function get_home_crumb() {

		$item = array();

		if ( false !== $this->options['home'] ) {

			if ( 'page' === $this->show_on_front ) {

				$item = array(
					'id' => get_option( 'page_on_front' ),
				);

			} elseif ( 'posts' === $this->show_on_front ) {

				$item = array(
					'text' => $this->options['home'],
					'url'  => $this->options['home_url'],
				);

			}
		}

		return $item;

	}

	/**
	 * Adds the root links to the breadcrumb.
	 */
	private function add_root_crumbs() {

		$items = array();

		$items[] = $this->get_home_crumb();

		/**
		 * Filters the root items.
		 *
		 * @param array $items An array list of the items.
		 */
		$items = apply_filters( 'evie_breadcrumb_root_items', $items );

		foreach ( $items as $item ) {
			if ( isset( $item['id'] ) ) {
				$this->add_single_post_crumb( $item['id'] );
			} else {
				$this->add_predefined_crumb( $item['text'], $item['url'] );
			}
		}
	}

	/**
	 * Adds links to the breadcrumb.
	 */
	private function add_crumbs() {
		$this->add_root_crumbs();
		$this->set_crumbs();
		$this->prepare_links();
		$this->links_to_string();
		$this->generate_breadcrumb();
	}

	/**
	 * Gets breadcrumb string using the singleton instance of this class
	 *
	 * @param array $options The options for the breadcrumb.
	 */
	public static function generate( $options = array() ) {

		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self( $options );
		}

		echo self::$instance->output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	/**
	 * Adds a single id based crumb to the crumbs property.
	 *
	 * @param int $id Post ID.
	 */
	public function add_single_post_crumb( $id ) {

		if ( false === $this->options['allow_duplicate'] ) {
			if ( array_search( $id, array_column( $this->crumbs, 'id' ), true ) !== false ) {
				return;
			}
		}

		$this->crumbs[] = array(
			'id' => $id,
		);
	}

	/**
	 * Gets the term's parents.
	 *
	 * @param object $term Term to get the parents for.
	 * @return array An array of the parents.
	 */
	private function get_term_parents( $term ) {
		$tax     = $term->taxonomy;
		$parents = array();
		while ( 0 !== $term->parent ) {
			$term      = get_term( $term->parent, $tax );
			$parents[] = $term;
		}

		return array_reverse( $parents );
	}

	/**
	 * Retrieves the hierachical ancestors for the current 'post'.
	 *
	 * @return array An array of the post ancestors.
	 */
	private function get_post_ancestors() {
		$ancestors = array();

		if ( isset( $this->post->ancestors ) ) {
			if ( is_array( $this->post->ancestors ) ) {
				$ancestors = array_values( $this->post->ancestors );
			} else {
				$ancestors = array( $this->post->ancestors );
			}
		} elseif ( isset( $this->post->post_parent ) ) {
			$ancestors = array( $this->post->post_parent );
		}

		/**
		 * Filters the ancestors for the breadcrumbs output.
		 *
		 * @param array $ancestors An array of the post ancestors.
		 */
		$ancestors = apply_filters( 'evie_breadcrumb_ancestors', $ancestors );

		if ( ! is_array( $ancestors ) ) {
			$ancestors = (array) $ancestors;
		}

		// Reverse the order so it's oldest to newest.
		$ancestors = array_reverse( $ancestors );

		return $ancestors;
	}

	/**
	 * Determines the crumbs which should be added to the breadcrumb.
	 */
	private function set_crumbs() {

		if ( ( 'page' === $this->show_on_front && is_front_page() ) || ( 'posts' === $this->show_on_front && is_home() ) ) {
			return;
		}

		if ( 'page' === $this->show_on_front && is_home() ) {
			// Add Posts page.
			$this->add_single_post_crumb( get_option( 'page_for_posts' ) );

		} elseif ( is_singular() ) {

			if ( isset( $this->post->post_parent ) && 0 === $this->post->post_parent ) {
				$this->add_taxonomy_crumbs_for_post();
			} else {
				$this->add_post_ancestor_crumbs();
			}

			if ( isset( $this->post->ID ) ) {
				$this->add_single_post_crumb( $this->post->ID );
			}
		} elseif ( is_post_type_archive() ) {

				$this->add_post_type_archive_crumb();

		} elseif ( is_tax() || is_tag() || is_category() ) {

			$this->add_crumbs_for_taxonomy();

		} elseif ( is_date() ) {

			if ( is_day() ) {
				$this->add_linked_month_year_crumb();
				$this->add_date_crumb();
			} elseif ( is_month() ) {
										$this->add_month_crumb();
			} elseif ( is_year() ) {
				$this->add_year_crumb();
			}
		} elseif ( is_author() ) {
			$user = get_queried_object();

			$this->add_predefined_crumb(
				$this->options['archive_prefix'] . $user->display_name,
				null,
				true
			);

		} elseif ( is_search() ) {

			$this->add_predefined_crumb(
				$this->options['search_prefix'] . '"' . esc_html( get_search_query() ) . '"',
				null,
				true
			);

		} elseif ( is_404() ) {

			$this->add_predefined_crumb(
				$this->options['404_prefix'],
				null,
				true
			);

		}

		/**
		 * Filters an array of the breadcrumb links.
		 *
		 * @param array $crumbs An array of the crumbs links.
		 */
		$this->crumbs = apply_filters( 'evie_breadcrumb_links', $this->crumbs );

		$this->crumb_count = count( $this->crumbs );
	}

	/**
	 * Add a term based crumb to the crumbs property
	 *
	 * @param object $term Term data object.
	 */
	private function add_term_crumb( $term ) {
		$this->crumbs[] = array(
			'term' => $term,
		);
	}

	/**
	 * Add a post type archive based crumb to the crumbs property
	 *
	 * @param string $post_type Post type. Default empty.
	 */
	private function add_post_type_archive_crumb( $post_type = '' ) {

		if ( empty( $post_type ) ) {
			$post_type = get_post_type();
		}

		$this->crumbs[] = array(
			'ptarchive' => $post_type,
		);
	}

	/**
	 * Add a predefined crumb to the crumbs property
	 *
	 * @param string $text Text string.
	 * @param string $url  URL string.
	 */
	private function add_predefined_crumb( $text, $url = '' ) {
		$this->crumbs[] = array(
			'text' => $text,
			'url'  => $url,
		);
	}

	/**
	 * Add hierarchical ancestor crumbs to the crumbs property for a single post
	 */
	private function add_post_ancestor_crumbs() {
		$ancestors = $this->get_post_ancestors();
		if ( is_array( $ancestors ) && ! empty( $ancestors ) ) {
			foreach ( $ancestors as $ancestor ) {
				$this->add_single_post_crumb( $ancestor );
			}
		}
	}

	/**
	 * Adds taxonomy crumbs to the crumbs property for a single post.
	 */
	private function add_taxonomy_crumbs_for_post() {

		if ( isset( $this->post->ID ) ) {

			$taxonomy = $this->options['taxonomy'];

			$terms = get_the_terms( $this->post, $taxonomy );

			if ( is_array( $terms ) && ! empty( $terms ) ) {

				$term = $this->get_primary_term( $this->post->ID, $taxonomy );

				if ( is_taxonomy_hierarchical( $taxonomy ) && 0 !== $term->parent ) {
					$parent_terms = $this->get_term_parents( $term );
					foreach ( $parent_terms as $parent_term ) {
						$this->add_term_crumb( $parent_term );
					}
				}

				$this->add_term_crumb( $term );
			}
		}

	}

	/**
	 * Retrieves the primary term for the post.
	 *
	 * @param int    $post_id  The post ID.
	 * @param string $taxonomy The taxonomy for the post.
	 * @return WP_Term The term for the post.
	 */
	private function get_primary_term( $post_id, $taxonomy ) {
		$terms        = get_the_terms( $post_id, $taxonomy );
		$primary_term = false;
		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			$primary_term = $terms[0];
		}
		return $primary_term;
	}

	/**
	 * Adds parent taxonomy crumbs to the crumb property for hierachical taxonomy
	 *
	 * @param object $term Term data object.
	 */
	private function maybe_add_term_parent_crumbs( $term ) {
		if ( is_taxonomy_hierarchical( $term->taxonomy ) && 0 !== $term->parent ) {
			foreach ( $this->get_term_parents( $term ) as $parent_term ) {
				$this->add_term_crumb( $parent_term );
			}
		}
	}

	/**
	 * Adds taxonomy parent crumbs to the crumbs property for a taxonomy
	 */
	private function add_crumbs_for_taxonomy() {
		$term = get_queried_object();
		$this->add_term_crumb( $term );
	}


	/**
	 * Adds month-year crumb to crumbs property
	 */
	private function add_linked_month_year_crumb() {
		$this->add_predefined_crumb(
			get_the_date( _x( 'F Y', 'monthly archives date format', 'evie' ) ),
			get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) )
		);
	}

	/**
	 * Adds (non-link) month crumb to crumbs property
	 */
	private function add_month_crumb() {
		$this->add_predefined_crumb(
			$this->options['archive_prefix'] . ' ' . esc_html( single_month_title( ' ', false ) ),
			null,
			true
		);
	}

	/**
	 * Adds (non-link) year crumb to crumbs property
	 */
	private function add_year_crumb() {
		$this->add_predefined_crumb(
			$this->options['archive_prefix'] . ' ' . esc_html( get_query_var( 'year' ) ),
			null,
			true
		);
	}

	/**
	 * Adds (non-link) date crumb to crumbs property.
	 */
	private function add_date_crumb() {
		$this->add_predefined_crumb(
			$this->options['archive_prefix'] . ' ' . get_the_date(),
			null,
			true
		);
	}

	/**
	 * Converts each crumb to a single breadcrumb string.
	 *
	 * @link http://support.google.com/webmasters/bin/answer.py?hl=en&answer=185417 Google documentation on RDFA
	 */
	private function prepare_links() {
		if ( ! is_array( $this->crumbs ) || empty( $this->crumbs ) ) {
			return;
		}

		foreach ( $this->crumbs as $index => $crumb ) {
			$link_info = $crumb; // Keep pre-set url/text combis.

			if ( isset( $crumb['id'] ) ) {
				$link_info = $this->get_link_info_for_id( $crumb['id'] );
			} elseif ( isset( $crumb['term'] ) ) {
				$link_info = $this->get_link_info_for_term( $crumb['term'] );
			} elseif ( isset( $crumb['ptarchive'] ) ) {
				$link_info = $this->get_link_info_for_ptarchive( $crumb['ptarchive'] );
			}

			$this->crumb_to_link( $link_info, $index );
		}
	}

	/**
	 * Retrieves the link url and text based on the post ID.
	 *
	 * @param int $id Post ID.
	 * @return array Array of link text and url
	 */
	private function get_link_info_for_id( $id ) {
		$link         = array();
		$link['url']  = get_permalink( $id );
		$link['text'] = esc_html( get_the_title( $id ) );

		return $link;
	}

	/**
	 * Retrieves link url and text based on term object
	 *
	 * @param object $term Term object.
	 *
	 * @return array Array of link text and url
	 */
	private function get_link_info_for_term( $term ) {
		$link         = array();
		$link['url']  = get_term_link( $term );
		$link['text'] = $term->name;

		return $link;
	}

	/**
	 * Retrieves link url and text based on post type
	 *
	 * @param string $post_type Post type.
	 *
	 * @return array Array of link text and url
	 */
	private function get_link_info_for_ptarchive( $post_type ) {
		$link          = array();
		$archive_title = '';

		$post_type_obj = get_post_type_object( $post_type );

		if ( is_object( $post_type_obj ) ) {
			if ( isset( $post_type_obj->labels->name ) && ! empty( $post_type_obj->labels->name ) ) {
				$archive_title = $post_type_obj->labels->name;
			} elseif ( isset( $post_type_obj->label ) && ! empty( $post_type_obj->label ) ) {
				$archive_title = $post_type_obj->label;
			} elseif ( isset( $post_type_obj->labels->menu_name ) && ! empty( $post_type_obj->labels->menu_name ) ) {
				$archive_title = $post_type_obj->labels->menu_name;
			} else {
				$archive_title = $post_type_obj->name;
			}
		}

		$link['url']  = get_post_type_archive_link( $post_type );
		$link['text'] = $archive_title;

		/**
		 * Filters the post type archive text and URL.
		 *
		 * @param array  $link      The link object of the post type archive.
		 * @param string $post_type Post type.
		 */
		$link = apply_filters( 'evie_breadcrumb_post_type_archive_link', $link, $post_type );

		return $link;
	}

	/**
	 * Creates a breadcrumb element string.
	 *
	 * @param array $link {
	 *        An array of the link info.
	 *
	 *        @type string $text       Link text.
	 *        @type string $url        Link url.
	 *        @type string $title      Optional. Link title attribute text.
	 * }
	 * @param int   $i    Index for the current breadcrumb.
	 */
	private function crumb_to_link( $link, $i ) {

		$link_output = '';

		if ( isset( $link['text'] ) && ! empty( $link['text'] ) ) {

			$link['text'] = trim( $link['text'] );

			if ( isset( $link['url'] ) && ! empty( $link['url'] ) && ( $i < ( $this->crumb_count - 1 ) ) ) {
				$this->links[] = sprintf(
					'<a href="%1$s"%2$s>%3$s</a>',
					esc_url( $link['url'] ),
					isset( $link['title'] ) ? ' title="' . esc_attr( $link['title'] ) . '"' : '',
					esc_html( $link['text'] )
				);

				$this->json_data[] = array(
					'@type'    => 'ListItem',
					'position' => absint( $i + 1 ),
					'name'     => esc_html( $link['text'] ),
					'item'     => esc_url( $link['url'] ),
				);

			} else {
				$this->links[] = sprintf(
					'<span class="breadcrumb-current">%1$s</span>',
					esc_html( $link['text'] )
				);

				$this->json_data[] = array(
					'@type'    => 'ListItem',
					'position' => absint( $i + 1 ),
					'name'     => esc_html( $link['text'] ),
				);
			}
		}
	}


	/**
	 * Create a complete breadcrumb string from an array of breadcrumb element strings
	 */
	private function links_to_string() {
		if ( is_array( $this->links ) && ! empty( $this->links ) ) {
			// Remove any effectively empty links.
			$links = array_map( 'trim', $this->links );
			$links = array_filter( $links );

			$this->output = implode( $this->separator, $links );
		}
	}

	/**
	 * Wrap a complete breadcrumb string in a Breadcrumb RDFA wrapper
	 */
	private function generate_breadcrumb() {
		if ( ! empty( $this->output ) ) {

			$json_ld_content = '';

			if ( true === $this->options['rich_snippet'] ) {
				$json_ld_content = '<script type="application/ld+json">' . wp_json_encode(
					array(
						'@context'        => 'https://schema.org',
						'@type'           => 'BreadcrumbList',
						'itemListElement' => $this->json_data,
					)
				) . '</script>';
			}

			$output = sprintf(
				'<%1$s class="%2$s">%3$s</%1$s>',
				$this->wrapper,
				esc_attr( $this->options['class'] ),
				$this->output . $json_ld_content
			);

			$output = apply_filters( 'evie_breadcrumb_output', $output );

			$this->output = $output;

		}
	}

}
