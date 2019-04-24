<?php
	function pedido_minimo_assets_init() {
		if (!is_admin()) {
			function pedido_minimo_styles() {
				wp_enqueue_style( 'pedido-minimo-custom-styles', PM_PLUGIN_URL . '/inc/assets/css/styles.css' );
			}
			add_action( 'wp_enqueue_scripts', 'pedido_minimo_styles', 99 );
		}
}
add_action('init', 'pedido_minimo_assets_init');
?>
