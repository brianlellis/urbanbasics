<?php

/**
 * POS Pro Customers Class
 *
 * @class    WC_POS_Pro_Customers
 * @package  WooCommerce POS Pro
 * @author   Paul Kilmurray <paul@kilbot.com.au>
 * @link     http://www.woopos.com.au
 */

class WC_POS_Pro_APIv2_Customers extends WC_POS_API_Abstract {

  /**
   * Constructor
   */
  public function __construct() {
    add_filter( 'pre_option_woocommerce_registration_generate_password', array( $this, 'woocommerce_registration_generate_password' ) );
    if( wc_pos_get_option( 'customers', 'generate_username' ) ){
      add_filter( 'pre_option_woocommerce_registration_generate_username', array( $this, 'woocommerce_registration_generate_username' ) );
    }

    add_filter( 'woocommerce_rest_insert_customer', array( $this, 'insert_customer' ), 10, 3 );
  }


  /**
   * @param bool            $result  Dispatch result, will be used if not empty.
   * @param WP_REST_Request $request Request used to generate the response.
   * @param string          $route   Route matched for the request.
   * @param array           $handler Route handler used for the request.
   * @return bool
   */
  public function dispatch_request($result, $request, $route, $handler) {

    if( isset($request['billing_address']) ) {
      $request->set_param('billing', array());
//      $request['billing'] = $request['billing_address'];
    }

    if( isset($request['copy_billing_address']) && $request['copy_billing_address'] ) {
      $request['shipping'] = $request['billing_address'];

    } elseif( isset($request['shipping_address']) ) {
      $request['shipping'] = $request['shipping_address'];
    }

    return $result;
  }


  /**
   * Backwards compat, save customer addresses
   *
   * @param WP_User         $user_data Data used to create the customer.
   * @param WP_REST_Request $request   Request object.
   * @param boolean         $creating  True when creating customer, false when updating customer.
   */
  public function insert_customer($user_data, $request, $creating) {
    $billing = array();
    $shipping = array();

    if( isset($request['billing_address']) && is_array($request['billing_address']) ) {
      $billing = $request['billing_address'];
      foreach( $request['billing_address'] as $meta_key => $meta_value ) {
        update_user_meta( $user_data->ID, 'billing_' . $meta_key, $meta_value );
      }
    }

    if( isset($request['copy_billing_address']) && $request['copy_billing_address'] ) {
      $shipping = $billing;

    } elseif( isset($request['shipping_address']) && is_array($request['shipping_address']) ) {
      $shipping = $request['shipping_address'];
    }

    foreach( $shipping as $meta_key => $meta_value ) {
      update_user_meta( $user_data->ID, 'shipping_' . $meta_key, $meta_value );
    }

  }


  /**
   * Force 'Automatically generate password'
   * WooCommerce > Settings > Accounts
   *
   * @return string
   */
  public function woocommerce_registration_generate_password(){
    return 'yes';
  }

  /**
   * Force 'Automatically generate username from customer email'
   * WooCommerce > Settings > Accounts
   *
   * @return string
   */
  public function woocommerce_registration_generate_username(){
    return 'yes';
  }

}