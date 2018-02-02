<?php

/**
 * WC REST API Class
 *
 * @class    WC_POS_Pro_API
 * @package  WooCommerce POS
 * @author   Paul Kilmurray <paul@kilbot.com.au>
 * @link     http://www.woopos.com.au
 */

class WC_POS_Pro_APIv2 {

  public function __construct() {
    if( ! is_pos() )
      return;

    add_filter( 'rest_pre_dispatch', array( $this, 'rest_pre_dispatch' ), 10, 3 );
    add_filter( 'rest_request_before_callbacks', array( $this, 'rest_request_before_callbacks' ), 10, 3 );
  }

  /**
   * @param mixed             $result Response to replace the requested version with. Can be anything
   *                                 a normal endpoint can return, or null to not hijack the request.
   * @param WP_REST_Server    $this Server instance.
   * @param WP_REST_Request   $request Request used to generate the response.
   * @return mixed
   */
  public function rest_pre_dispatch( $result, $server, $request ) {
    // endpoint hasn't been matched here and request has not been parsed
    // not much good to us
    return $result;
  }


  /**
   * @param $response
   * @param $handler
   * @param $request
   * @return mixed
   */
  public function rest_request_before_callbacks( $response, $handler, $request ) {
    $wc_api_handler = get_class($handler['callback'][0]);

    switch($wc_api_handler) {
      case 'WC_REST_Products_Controller':
      case 'WC_REST_Product_Variations_Controller':
        new WC_POS_Pro_APIv2_Products();
        // hack top allow decimal quantities
        if( $request->get_method() == 'PATCH' && array_key_exists( 'stock_quantity', $request->get_json_params() ) ) {
          return null;
        }
        break;
      case 'WC_REST_Orders_Controller':
        new WC_POS_Pro_APIv2_Orders();
        break;
      case 'WC_REST_Customers_Controller':
        new WC_POS_Pro_APIv2_Customers();
        // hack to allow customer creation without password & username
        return null;
        break;
      case 'WC_REST_Coupons_Controller':
        new WC_POS_Pro_APIv2_Coupons();
        break;
    }

    return $response;
  }

}