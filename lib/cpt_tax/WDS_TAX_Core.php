<?php

/**
 * Plugin class for generating custom Taxonomies.
 *
 */
class WDS_TAX_Core {

	private $singular;
	public $single;
	private $plural;
	public $plurals;
	private $registered;
	public $slug;
	private $object_types;
	private $args;


	public function __construct( $tax, $object_types = array(), $args = array() ) {

		if( ! $tax )
			wp_die( 'No taxonomy given' );

		if ( is_string( $tax ) ) {
			$this->singular = $tax;
			$this->plural   = $tax .'s';
			$this->registered     = sanitize_title( $this->plural );
		} elseif ( is_array( $tax ) && $tax[0] ) {
			$this->singular = $tax[0];
			$this->plural   = !isset( $tax[1] ) || !is_string( $tax[1] ) ? $tax[0] .'s' : $tax[1];
			$this->registered   = !isset( $tax[2] ) || !is_string( $tax[2] ) ? sanitize_title( $this->plural ) : $tax[2];
		} else {
			wp_die( 'Taxonomy incorrectly registered' );
		}

		$this->single       = $this->singular;
		$this->plurals      = $this->plural;
		$this->slug         = $this->registered;

		$this->object_types = (array) $object_types;
		$this->args         = (array) $args;

		add_action( 'init', array( $this, 'tax_loop' ) );
		add_action( 'restrict_manage_posts', array( $this, 'add_sort_filter' ) );

	}

	public function tax_loop() {

		$defaults = array(
			'hierarchical' => true,
			'labels' => array(
				'name' => $this->plural,
				'singular_name' => $this->singular,
				'search_items' =>  'Search '.$this->plural,
				'all_items' => 'All '.$this->plural,
				'parent_item' => 'Parent '.$this->singular,
				'parent_item_colon' => 'Parent '.$this->singular.':',
				'edit_item' => 'Edit '.$this->singular,
				'update_item' => 'Update '.$this->singular,
				'add_new_item' => 'Add New '.$this->singular,
				'new_item_name' => 'New '.$this->singular.' Name',
			),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => $this->registered ),
		);

		$args = wp_parse_args( $this->args, $defaults );

		register_taxonomy( $this->registered, $this->object_types, $args );

	}

	public function get_term_or_create( $type_name ) {
		$term = get_term_by( 'name', $type_name, $this->registered );

		if ( !$term ) {
			$term = wp_insert_term( $type_name, $this->registered );
			if ( is_wp_error( $term ) )
				return false;

			$term = get_term_by( 'id', $term['term_id'], $this->registered );
		}
		return $term;
	}

	public function add_sort_filter() {

		$screen = get_current_screen();

		if ( !in_array( str_replace( 'edit-', '', $screen->id ), $this->object_types ) )
			return;

		$terms = get_terms( $this->registered, array( 'hide_empty' => 0 ) );
		$current = isset( $_GET[$this->registered] ) ? $_GET[$this->registered] : false;
		echo "<select name='{$this->registered}'>\n";
		echo "<option value=''>Filter by {$this->single}</option>\n";
		foreach ( $terms as $term ) {

			$selected = selected( $current, $term->slug, false );
			echo "<option value='{$term->slug}' $selected>{$term->name}</option>\n";
		}
		echo "</select>\n";
	}
}