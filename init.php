<?php
/*------------------------------------------------------------------------------------------------------------------
Plugin Name: Pedido Mínimo Wc
Plugin URI: https://github.com/soyluismunoz/pedido-minimo-wc
Description: Plugin para configurar el valor mínimo o la cantidad mínima de elementos para la finalización de pedidos en WooCommerce. 
El plugin también permite seleccionar una función de usuario de Wordpress para aplicar las reglas configuradas.
Version: 1.0
Author: Luis Munoz
Author URI: https://soyluismunoz.com
Text Domain: pedido-minimo-wc
License: GPL2
---------------------------------------------------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}
function pedido_minimo_domain_text_init() {
	$pedido_minimo_rel_path = basename( dirname( __FILE__ ) ) . '/languages';
	load_plugin_textdomain( 'pedido-minimo-wc', false, $pedido_minimo_rel_path );
}
add_action('plugins_loaded', 'pedido_minimo_domain_text_init');


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define('PM_PLUGIN_URL', plugins_url('', __FILE__));
	define('PM_PLUGIN_DIR', plugin_dir_path(__FILE__));

	require_once( PM_PLUGIN_DIR . '/inc/load-assets.php');
	require_once( PM_PLUGIN_DIR . '/inc/load-admin-settings.php');
	require_once( PM_PLUGIN_DIR . '/inc/load-pedido-minimo.php');
} else {
	exit;
}
