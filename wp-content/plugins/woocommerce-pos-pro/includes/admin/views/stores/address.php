<?php
/**
 * Address meta box
 */
?>

<!-- <div class="form-col">
	<div class="form-group">
		<label for="pos_company"><?php /* translators: woocommerce */ _e( 'Company', 'woocommerce' ) ?></label>
		<input name="pos_company" id="pos_company" placeholder="<?php bloginfo( 'name' ); ?>" type="text" value="<?php echo get_post_meta( $post->ID, '_company', true ); ?>" class="">
	</div>
</div> -->
<div class="form-col-2">
  <div class="form-group">
    <label for="pos_address_1"><?php /* translators: woocommerce */ _e( 'Address 1', 'woocommerce' ) ?></label>
    <input name="pos_address_1" id="pos_address_1" type="text" value="<?php echo get_post_meta( $post->ID, '_address_1', true ); ?>" class="">
  </div>
  <div class="form-group">
    <label for="pos_address_2"><?php /* translators: woocommerce */ _e( 'Address 2', 'woocommerce' ) ?></label>
    <input name="pos_address_2" id="pos_address_2" type="text" value="<?php echo get_post_meta( $post->ID, '_address_2', true ); ?>" class="">
  </div>
</div>
<div class="form-col-2">
  <div class="form-group">
    <label for="pos_city"><?php /* translators: woocommerce */ _e( 'City', 'woocommerce' ) ?></label>
    <input name="pos_city" id="pos_city" type="text" value="<?php echo get_post_meta( $post->ID, '_city', true ); ?>" class="">
  </div>
  <div class="form-group">
    <label for="pos_state"><?php /* translators: woocommerce */ _e( 'State/County', 'woocommerce' ) ?></label>
    <input type="text" name="pos_state" id="pos_state" value="<?php echo $state ?>" <?php if($states) echo 'style="display:none"' ?>>
    <select name="pos_state" id="pos_state" <?php if(!$states) echo 'style="display:none"' ?>>
      <?php if($states): foreach( $states as $code => $name ): ?>
        <option value="<?php echo $code ?>" <?php if( $state == $code ) echo 'selected="selected"' ?>><?php echo $name ?></option>
      <?php endforeach; endif; ?>
    </select>
  </div>
</div>
<div class="form-col-2">
  <div class="form-group">
    <label for="pos_postcode"><?php /* translators: woocommerce */ _e( 'Postcode', 'woocommerce' ) ?></label>
    <input name="pos_postcode" id="pos_postcode" type="text" value="<?php echo get_post_meta( $post->ID, '_postcode', true ); ?>" class="small-input">
  </div>
  <div class="form-group">
    <label for="pos_country"><?php /* translators: woocommerce */ _e( 'Country', 'woocommerce' ) ?></label>
    <select name="pos_country" id="pos_country">
      <?php foreach( $countries as $code => $name ): ?>
        <option value="<?php echo $code ?>" <?php if( $country == $code ) echo 'selected="selected"' ?>><?php echo $name ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<script type="text/javascript">
  jQuery(function($) {
    var input = $('input#pos_state');
    var select = $('select#pos_state');

    $('#pos_country').change( function() {
      input.hide();
      select.show().html('<option><?php /* translators: wordpress */ _e( 'Loading&#8230;' ) ?></option>');
      var country = $(this).val();
      $.getJSON( ajaxurl, {
        action: 'wc_pos_pro_store_states',
        country: country,
        security: '<?php echo wp_create_nonce('woocommerce-pos') ?>'
      }, function( data ) {
        var options = [];
        if(!data || data.length === 0){
          select.hide();
          input.show();
        }
        $.each( data, function( key, val ) {
          options.push( "<option value='" + key + "'>" + val + "</option>" );
        });
        select.html(options.join( '' ));
      });
    });
  });
</script>

<br class="clear">