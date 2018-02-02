<?php

/**
 * POS Pro Orders Class
 *
 * @class    WC_POS_Pro_API_Orders
 * @package  WooCommerce POS Pro
 * @author   Paul Kilmurray <paul@kilbot.com.au>
 * @link     http://www.woopos.com.au
 */

class WC_POS_Pro_APIv2_Orders extends WC_POS_API_Abstract {

  public function __construct() {
    add_filter( 'woocommerce_rest_pre_insert_shop_order_object', array( $this, 'pre_insert_shop_order_object' ), 10, 3 );
    add_action( 'woocommerce_pos_process_payment', array( $this, 'process_payment' ), 5, 2 );
  }


  /**
   * Create order complete
   * @param $order
   */
  public function pre_insert_shop_order_object( $order ){

    $store_id = get_user_option( 'woocommerce_pos_store' );

    if( is_numeric( $store_id ) ){
      update_post_meta($order->get_id(), '_pos_store', $store_id );
      update_post_meta($order->get_id(), '_pos_store_title', get_the_title( $store_id ));
    }

    return $order;
  }


  /**
   * @param $payment_details
   * @param $order
   */
  public function process_payment( $payment_details, $order ){

    // prepare payment data
    foreach($payment_details as $key => $value){

      $_POST[$key] = $value;

      // match credit card number
      if(strpos($key, 'number') !== false){
        $_POST['number'] = $value;

        // match expiry month
      } elseif(strpos($key, 'month') !== false){
        $_POST['month'] = $value;

        // match expiry year
      } elseif(strpos($key, 'year') !== false){
        $_POST['year'] = $value;

        // match cvv
      } elseif(strpos($key, 'cvv') !== false){
        $_POST['cvv'] = $value;
      }

      if($key == 'stripe_token'){
        $_POST['stripe_token'] = $value;
        $_POST['stripeToken'] = $value;
      }

    }

  }

}