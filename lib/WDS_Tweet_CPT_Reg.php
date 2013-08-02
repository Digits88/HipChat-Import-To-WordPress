<?php

if ( !class_exists( 'WDS_CPT_Core' ) )
	require_once( 'cpt_tax/WDS_CPT_Core.php' );

/**
 * Registers the CRM CPT.
 */
class WDS_Tweet_CPT_Reg extends WDS_CPT_Core {

	/**
	 * Holds copy of instance, so other plugins can remove our hooks.
	 *
	 * @since 1.0
	 * @link http://core.trac.wordpress.org/attachment/ticket/16149/query-standard-format-posts.php
	 * @link http://twitter.com/#!/markjaquith/status/66862769030438912
	 *
	 */
	static $instance;
	public $prefix = 'wds-';
	public $meta;

	function __construct() {

		self::$instance = $this;

		parent::__construct( array( 'Tweet', 'Tweets', 'wds-tweets' ), array( 'supports' => array( 'title', 'editor' ) ) );

		add_filter( 'cmb_meta_boxes', array( $this, 'metaboxes' ) );
		add_action( 'admin_head', array( $this, 'tweetcss' ) );
	}

	public function tweetcss() {
		$screen = get_current_screen();
		if ( !$screen || $screen->id != 'edit-'. $this->slug )
			return;
		?>
		<style type="text/css">
		.manage-column.column-content {
			width: 50%;
		}
		</style>
		<?php
	}

	public function columns( $columns ) {
		$pos = array_search( 'date', array_keys( $columns ) );
		$start = array_slice( $columns, 0, $pos );
		$end = array_slice( $columns, $pos );
		$new = array( 'content' => 'Message' );

		return array_merge( $start, $new, $end );
	}

	public function columns_display( $column ) {
		global $post;
		if ( $column == 'content' )
			the_content();
	}

	public function metaboxes( $meta_boxes ) {

		$fields = array();
		foreach ( (array) $this->meta() as $key => $value ) {
			$field = array(
				'name' => $value[0],
				'id'   => $key,
				'type' => $value[1]
			);
			if ( isset( $value[2] ) )
				$field['desc'] = $value[2];
			if ( isset( $value[3] ) )
				$field['options'] = $value[3];

			$fields[] = $field;
		}

		$meta_boxes[] = parent::metabox_defaults( array( 'fields' => $fields ) );

		return $meta_boxes;
	}

	public function meta() {
		$this->meta = array(
			$this->prefix.'author' => array( 'Quote Author', 'text' ),
			$this->prefix.'hipchat-room' => array( 'HipChat Room', 'text' ),
		);
		return $this->meta;
	}

}
