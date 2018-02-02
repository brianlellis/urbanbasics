<?php

/**
 * POS Pro Products Class
 *
 * @class    WC_POS_Pro_Products
 * @package  WooCommerce POS Pro
 * @author   Paul Kilmurray <paul@kilbot.com.au>
 * @link     http://www.woopos.com.au
 */

class WC_POS_Pro_APIv2_Products extends WC_POS_API_Abstract {

  /**
   * Constructor
   */
  public function __construct() {
    add_filter( 'woocommerce_rest_pre_insert_product_object', array( $this, 'pre_insert_product_object' ), 10, 3 );
    add_filter( 'woocommerce_rest_pre_insert_product_variation_object', array( $this, 'pre_insert_product_object' ), 10, 3 );

    add_filter( 'woocommerce_pos_barcode_meta_key', array( $this, 'barcode_meta_key' ) );
  }


  /**
   * @param $product
   * @param $request
   * @param $creating
   */
  public function pre_insert_product_object( $product, $request, $creating ) {

    // backwards compat
    if ( isset( $request['managing_stock'] ) ) {
      $product->set_manage_stock($request['managing_stock']);
    }

    if( isset( $request['taxable'] ) ) {
      $tax_status = $request['taxable'] ? 'taxable' : 'none';
      $product->set_tax_status( $tax_status );
    }

    if( isset( $request['on_sale'] ) ) {
      $sale_price = $request['on_sale'] ? 0 : '';
      $product->set_sale_price( $sale_price );
    }

    return $product;
  }


  /**
   * @param $key
   * @return bool
   */
  public function barcode_meta_key( $key ){
    $custom_key = WC_POS_Settings::get_option( 'general', 'barcode_field' );
    return $custom_key ? $custom_key : $key;
  }

}