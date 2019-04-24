<?php
add_action( 'woocommerce_check_cart_items', 'pedido_minimo_wc_function' );
function pedido_minimo_wc_function() {
	$pedido_minimo_onoff = get_option( 'pedido-minimo-wc-onoff', false );

	$pedido_minimo_wc_get_current_role = pedido_minimo_wc_get_current_role();

	$pedido_minimo_users = get_option( 'pedido-minimo-wc-users', false );

	    if( (is_cart() || is_checkout()) && $pedido_minimo_onoff == 'yes' && ($pedido_minimo_users == '' || $pedido_minimo_users == $pedido_minimo_wc_get_current_role) ) {
	        
	        global $woocommerce;
	 
	        $total_cart_value = WC()->cart->subtotal;
	        $total_cart_quantity = WC()->cart->get_cart_contents_count();

			$pedido_minimo_operation = get_option( 'pedido-minimo-wc-operation', false );
			$pedido_minimo_value = get_option( 'request-minimo-wc-quantity', false );
			$pedido_minimo_quantity = get_option( 'pedido-minimo-wc-quantity', false );

	        if( $pedido_minimo_operation == 'valor' ) {
		        if( $total_cart_value < $pedido_minimo_value ) {
					$balance = wc_price($pedido_minimo_value - $total_cart_value);
					$message = '<p>'.esc_html__( 'Usted necesita comprar más %s para alcanzar el valor mínimo de la tienda.', 'pedido-minimo-wc').'</p></div>';

					if ( $total_cart_value !== 0 ) {
							$moneda = get_woocommerce_currency_symbol();
				            wc_add_notice( sprintf( '<div class="alert_pedido_minimo"><p>'.esc_html__('La solicitud debe tener un valor mínimo de', 'pedido-minimo-wc').' <strong>'.$moneda.' %s</strong>.</p>'.'<p>'.esc_html__('El valor total de su pedido actual es', 'pedido-minimo-wc').' <strong> %s</strong>.</p>'.$message, wc_price($pedido_minimo_value), wc_price($total_cart_value), $balance ), 'error' );
					}
	        	}
    		} elseif( $pedido_minimo_operation == 'quantity' ) {
		        if( $total_cart_quantity < $pedido_minimo_quantity ) {
					$balance = $pedido_minimo_quantity - $total_cart_quantity;
					if ( $balance == 1 ) {
						$txtItem = 'item';
					} elseif ($balance > 1) {
						$txtItem = 'items';
					}
					$message = '<p>'.esc_html__( 'Usted necesita comprar %s '.$txtItem.' para alcanzar la cantidad mínima de la tienda.', 'pedido-minimo-wc').'</p></div>';

					if ( $total_cart_quantity !== 0 ) {
						if ( $total_cart_quantity == 1 ) {
							$txtItem = 'item';
						} else {
							$txtItem = 'items';
						}

				            wc_add_notice( sprintf( '<div class="alert_pedido_minimo">
				            	<p>'.esc_html__('La solicitud debe tener la cantidad mínima de', 'pedido-minimo-wc').' <strong>%s items</strong>.</p>'.'<p>'.esc_html__('Su pedido ahora tiene', 'pedido-minimo-wc').' <strong> %s '.$txtItem.'</strong>.</p>'.$message, $pedido_minimo_quantity, $total_cart_quantity, $balance ), 'error' );
					}
	        	}
    		}
		}
}


function pedido_minimo_wc_get_current_role() {
  if( is_user_logged_in() ) {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;
    return $role[0];
  } else {
    return false;
  }
}