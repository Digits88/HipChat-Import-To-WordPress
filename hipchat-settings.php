<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo $this->admin_name; ?></h2>
	<form class="" method="post" action="options.php">
		<?php settings_fields( $this->admin_slug ); ?>
		<?php do_settings_sections( $this->admin_slug ); ?>
		<p class="submit">
			<input name="submit" type="submit" class="button-primary" value="<?php _e( 'Save' ); ?>" />
		</p>
	</form>
</div>