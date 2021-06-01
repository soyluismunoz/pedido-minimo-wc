<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

add_action('admin_head', 'pedido_minimo_admin_css');
function pedido_minimo_admin_css() {
    if (is_admin()) {
        $page = isset($_GET['page']);
        $tab = isset($_GET['tab']);
        if ( $page == 'wc-settings' && $tab == 'settings_pedido_minimo_tab' ) {
          echo '
            <style>
                #pedido-minimo-wc-value,
                #pedido-minimo-wc-quantity {
                    max-width: 100px;
                    text-align: center;
                }
                #pedido-minimo-wc-operation,
                #pedido-minimo-wc-users {
                    max-width: 250px;
                }
                span.description {
                    float: left;
                    clear: both;
                    width: 100%;
                    margin: 5px 0 0 5px;
                }
                .woocommerce table.form-table th {
                    padding-right: 10px;
                    width: 260px;
                    text-align: right;
                }
                h2 {
                    text-align: center;
                    font-size: 2em;
                    text-transform: uppercase;
                    font-weight: 600;
                    margin: 50px 0 10px 0;
                    border-bottom: 1px dotted #ccc;
                    padding-bottom: 20px;
                }

            </style>';

            echo '
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        if (jQuery("#pedido-minimo-wc-onoff").prop("checked") == false) { 
                            jQuery(".pedido-minimo-wc-admin-field").attr("disabled", "disabled");
                        }
                        jQuery("#pedido-minimo-wc-onoff").on("click", function() {
                            if (this.checked) { 
                                jQuery(".pedido-minimo-wc-admin-field").removeAttr("disabled");
                            } else {
                                jQuery(".pedido-minimo-wc-admin-field").attr("disabled", "disabled");
                            }
                        });
                        
                    });
                </script>
            ';
        }
    }
}


class WC_Settings_Pedido_Minimo_Tab {


    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_pedido_minimo_tab', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_pedido_minimo_tab', __CLASS__ . '::update_settings' );
    }

    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_pedido_minimo_tab'] = __( 'Pedido Mínimo WC', 'pedido-minimo-wc' );
        return $settings_tabs;
    }

    public static function update_settings() {
        woocommerce_update_options( self::get_option() );
    }

    public static function settings_tab() {
        woocommerce_admin_fields( self::get_option() );
    }

	public static function get_option() {
            global $woocommerce;
            $moneda = get_woocommerce_currency_symbol();
        $settings = [
            'section_title' => array(
                'name'     => __( 'Pedido Mínimo wc', 'pedido-minimo-wc' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'pedido-minimo-wc-section-title'
            ),
            'onoff' => array(
                'name' => __( 'Plugin activated', 'pedido-minimo-wc' ),
                'type' => 'checkbox',
                'desc' => __( 'Activate or deactivate the plugin.', 'pedido-minimo-wc' ),
                'id'   => 'pedido-minimo-wc-onoff',
            ),
            'operation' => array(
                'name'  => __( 'operating mode', 'pedido-minimo-wc' ),
                'type'  => 'select',
                'desc'  => __( 'Select if the plugin will work by value or quantity.', 'pedido-minimo-wc' ),
                'id'    => 'pedido-minimo-wc-operation',
                'class' => 'pedido-minimo-wc-admin-field',
                'options' => array(
                  'value'       => __( 'value', 'pedido-minimo-wc' ),
                  'quantity'  => __( 'Quantity', 'pedido-minimo-wc' ),
                ),
            ),
            'value' => array(
                'name' => __( 'Minimum value of the request in ', 'pedido-minimo-wc' ) . $moneda ,
                'type' => 'text',
                'desc' => __( 'Requests with a lower value are not completed.', 'pedido-minimo-wc' ),
                'id'   => 'pedido-minimo-wc-value',
                'class' => 'pedido-minimo-wc-admin-field',
            ),
            'quantity' => array(
                'name' => __( 'Minimum quantity of items in the order', 'pedido-minimo-wc' ),
                'type' => 'text',
                'desc' => __( 'Requests with fewer items are not finalized.', 'pedido-minimo-wc' ),
                'id'   => 'pedido-minimo-wc-quantity',
                'class' => 'pedido-minimo-wc-admin-field',
            ),
            'usuarios' => array(
                'name' => __( 'Select a user role', 'pedido-minimo-wc' ),
                'type' => 'select',
                'desc' => __( 'The plugin rules only apply to the selected user role.', 'pedido-minimo-wc' ),
                'id'   => 'pedido-minimo-wc-users',
                'class' => 'pedido-minimo-wc-admin-field',
                'options' => wc_pedido_minimo_get_role_names(),
            ),
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'pedido-minimo-wc'
            )
        ];

	    return apply_filters( 'wc_settings_pedido_minimo_tab_settings', $settings );
	}


}
WC_Settings_Pedido_Minimo_Tab::init();


function wc_pedido_minimo_get_role_names() {
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();
        $select_null = ['' => 'Todos los usuarios'];
        $all_roles = $wp_roles->get_names();
        $full_array = array_merge($select_null, $all_roles);
    return $full_array;
}