<?php

/**
 * WP Admin Class
 *
 * @class    WC_POS_Pro_Admin
 * @package  WooCommerce POS
 * @author   Paul Kilmurray <paul@kilbot.com.au>
 * @link     http://www.woopos.com.au
 */

class WC_POS_Pro_Ajax {

  /**
   * Constructor
   */
  public function __construct() {

    // need this for save settings via ajax
    add_filter( 'woocommerce_pos_capabilities', array( 'WC_POS_Pro_Admin_Settings', 'capabilities' ) );

    // pro support email
    add_filter( 'woocommerce_pos_support_email', array( $this, 'support_email') );

    $ajax_events = array(
      'barcode_fields' => $this,
      'store_states' => $this
    );

    foreach ( $ajax_events as $ajax_event => $class ) {
      // check_ajax_referer
      add_action( 'wp_ajax_wc_pos_pro_' . $ajax_event, array( $this, 'check_ajax_referer' ), 1 );
      // trigger method
      add_action( 'wp_ajax_wc_pos_pro_' . $ajax_event, array( $class, $ajax_event ) );
    }

  }

  /**
   * @param $email
   * @return string
   */
  public function support_email($email){
    return 'pro@wcpos.com';
  }

  /**
   * Parse an RFC3339 datetime into a MySQl datetime
   * mirrors woocommerce/includes/api/class-wc-api-server.php
   *
   * @param $datetime
   * @return string
   */
//  protected function parse_datetime( $datetime ) {
//    // Strip millisecond precision (a full stop followed by one or more digits)
//    if ( strpos( $datetime, '.' ) !== false ) {
//      $datetime = preg_replace( '/\.\d+/', '', $datetime );
//    }
//    // default timezone to UTC
//    $datetime = preg_replace( '/[+-]\d+:+\d+$/', '+00:00', $datetime );
//    try {
//      $datetime = new DateTime( $datetime, new DateTimeZone( 'UTC' ) );
//    } catch ( Exception $e ) {
//      $datetime = new DateTime( '@0' );
//    }
//    return $datetime->format( 'Y-m-d H:i:s' );
//  }

  /**
   * @todo: this should go straight to WC_POS_Pro_Admin_Settings_General?
   */
  public function barcode_fields(){
    $q = isset( $_GET['q'] ) ? $_GET['q'] : '';
    $result = WC_POS_Pro_Admin_Settings_General::search_barcode_fields($q);
    WC_POS_Server::response($result);
  }

  /**
   * Verifies the AJAX request
   */
  public function check_ajax_referer(){
    $pass = check_ajax_referer( WC_POS_PLUGIN_NAME, 'security', false );
    if(!$pass){
      $result = new WP_Error(
        'woocommerce_pos_invalid_nonce',
        __( 'Invalid security nonce', 'woocommerce-pos' ),
        array( 'status' => 401 )
      );
      WC_POS_Server::response($result);
    }
  }

  /**
   *
   */
  public function store_states(){
    $country = isset($_GET['country']) ? $_GET['country'] : '';
    $states = WC()->countries->get_states($country);
    if(!$states){
      $states = array();
    }
    return self::response($states);
  }

  /**
   * The below functions closely resemble output from the WC REST API
   * This keeps response handling in the POS somewhat consistent
   * between API and AJAX calls
   *
   * Output the result
   * @param $result
   */
  static public function response($result){
    header( 'Content-Type: application/json; charset=utf-8' );
    if (is_wp_error($result)) {
      $data = $result->get_error_data();
      if ( is_array( $data ) && isset( $data['status'] ) ) {
        status_header( $data['status'] );
      }
      $result = self::error_to_array( $result );
    }
    echo json_encode( $result );
    die();
  }

  /**
   * Convert wp_error to array
   * @param $error
   * @return array
   */
  static private function error_to_array( $error ) {
    $errors = array();
    foreach ( (array) $error->errors as $code => $messages ) {
      foreach ( (array) $messages as $message ) {
        $errors[] = array( 'code' => $code, 'message' => $message );
      }
    }
    return array( 'errors' => $errors );
  }

}