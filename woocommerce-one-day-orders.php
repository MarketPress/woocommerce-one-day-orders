<?php
/**
 * Plugin Name:	WooCommerce One Day Orders
 * Description:	Gives you the possibility to show only orders of one special day in the shop order view
 * Version:		1.0
 * Author:		MarketPress
 * Author URI:	https://marketpress.de
 * Text Domain:	woocommerce-one-day-orders
 * Domain Path:	/languages
 * Licence:		GPLv3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

if ( ! function_exists( 'woocommerce_one_day_orders_init' ) ) {

	/**
	* Init Plugin
	*
	* @author MarketPress
	* @wp-hook plugins_loaded
	* @return void
	**/
	function woocommerce_one_day_orders_init() {

		if ( is_admin() ) {
			add_action( 'restrict_manage_posts', 'woocommerce_on_day_orders_init_restrict_manage_posts', 10, 2 );
			add_action( 'parse_query', 'woocommerce_one_day_orders_parse_query' );
			add_action( 'init', 'woocommerce_one_day_orders_load_textdomain' );
		}
		
	}

	/**
	* Add list filter
	*
	* @author MarketPress
	* @wp-hook restrict_manage_posts
	* @param String $post_type
	* @param String $which
	* @return void
	**/
	function woocommerce_on_day_orders_init_restrict_manage_posts( $post_type, $which ) {
		
		if ( $post_type == 'shop_order' ) {
			
			$woocommerce_one_day = isset( $_REQUEST[ 'woocommerce_one_day_orders' ] ) ? $_REQUEST[ 'woocommerce_one_day_orders' ] : '';

			?>
			<input type="search" class="date-picker-field" name="woocommerce_one_day_orders" id="woocommerce_one_day_orders" value="<?php echo $woocommerce_one_day; ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="<?php echo __( 'Choose Date', 'woocommerce-one-day-orders' ); ?>" />
			<?php
		}

	}

	/**
	* Edit Query
	* 
	* @author MarketPress
	* @wp-hook parse_query
	* @param WP_Query $wp_query
	* @return void
	**/
	function woocommerce_one_day_orders_parse_query( $wp_query ) {

		if ( $wp_query->query[ 'post_type' ] == 'shop_order' ) {
			
			if ( isset( $_REQUEST[ 'woocommerce_one_day_orders' ] ) && $_REQUEST[ 'woocommerce_one_day_orders' ] != '' ) {

				$selected_day = new DateTime( $_REQUEST[ 'woocommerce_one_day_orders' ] );

				$datequery = array(
			        array(
			            'year'		=> intval( $selected_day->format( 'Y' ) ),
			            'monthnum'	=> intval( $selected_day->format( 'm' ) ),
			            'day' 		=> intval( $selected_day->format( 'd' ) )
			        )
			    );

				$wp_query->set( 'date_query', $datequery );
			}

		}

	}

	/**
	* Load Textdomain
	* 
	* @author MarketPress
	* @wp-hook admin_init
	* @return void
	**/
	function woocommerce_one_day_orders_load_textdomain() {
		load_plugin_textdomain( 'woocommerce-one-day-orders', FALSE, untrailingslashit( dirname( plugin_basename( __FILE__) ) ) . DIRECTORY_SEPARATOR . 'languages' );
	}

	add_action( 'plugins_loaded', 'woocommerce_one_day_orders_init' );

}
