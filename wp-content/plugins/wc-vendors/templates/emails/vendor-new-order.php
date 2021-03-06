<?php
/**
 * Vendor new order email
 *
 * @author WC Vendors
 * @package WooCommerce/Templates/Emails/HTML
 * @version 1.9.9
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$billing_first_name = ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->billing_first_name : $order->get_billing_first_name(); 
$billing_last_name 	= ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->billing_last_name : $order->get_billing_last_name(); 
$billing_email 		= ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->billing_email : $order->get_billing_email(); 
$billing_phone 		= ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->billing_phone : $order->get_billing_phone(); 
$order_date 		= ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->order_date : $order->get_date_created();  

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( 'You have received an order from %s. Their order is as follows:', 'wcvendors' ), $billing_first_name . ' ' . $billing_last_name ); ?></p>

<?php do_action( 'woocommerce_email_before_order_table', $order, true ); ?>

<h2><?php printf( __( 'Order: %s', 'wcvendors'), $order->get_order_number() ); ?> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order_date ) ), date_i18n( wc_date_format(), strtotime( $order_date ) ) ); ?>)</h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'wcvendors' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'wcvendors' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'wcvendors' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php 

			if ( version_compare( WC_VERSION, '2.7', '<' ) ){ 

				echo $order->email_order_items_table( array(
					'show_sku'      => true,
					'show_image'    => false,
					'image_size'    => array( 32, 32 ),
					'plain_text'    => false,
					'sent_to_admin' => false
				) ); 

			} else { 
				echo wc_get_email_order_items( $order, array(
					'show_sku'      => true,
					'show_image'    => false,
					'image_size'    => array( 32, 32 ),
					'plain_text'    => false,
					'sent_to_admin' => false,
				) );
			}

			?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td style="text-align:left; border: 1px solid #eee; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action('woocommerce_email_after_order_table', $order, true); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, true ); ?>

<h2><?php _e( 'Customer details', 'wcvendors' ); ?></h2>

<?php if ( $billing_email ) : ?>
	<p><strong><?php _e( 'Email:', 'wcvendors' ); ?></strong> <?php echo $billing_email; ?></p>
<?php endif; ?>
<?php if ( $billing_phone ) : ?>
	<p><strong><?php _e( 'Tel:', 'wcvendors' ); ?></strong> <?php echo $billing_phone; ?></p>
<?php endif; ?>

<?php wc_get_template( 'emails/email-addresses.php', array( 'order' => $order ) ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>