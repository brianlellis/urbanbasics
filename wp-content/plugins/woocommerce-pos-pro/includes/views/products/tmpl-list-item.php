<div class="img"><img src="{{featured_src}}" title="#{{id}}"></div>
<div class="title">
  {{#is type 'variation'}}
  <strong>{{name}}</strong>
  {{else}}
  <strong contenteditable="true">{{name}}</strong>
  {{/is}}
  {{#with product_attributes}}
  <i class="icon-info-circle" data-toggle="tooltip" title="
    <dl>
      {{#each this}}
      <dt>{{name}}:</dt>
      <dd>{{#list options ', '}}{{this}}{{/list}}</dd>
      {{/each}}
    </dl>
    "></i>
  {{/with}}
  {{#with product_variations}}
  <ul class="variations">
    {{#each this}}
    <li data-toggle="buttons">
      <strong>{{name}}:</strong> {{#list options ', '}}<a href="#" data-name="{{../name}}">{{this}}</a>{{/list}}
    </li>
    {{/each}}
  </ul>
  <small>
    <a href="#" data-action="expand" class="expand-all"><?php /* translators: woocommerce */ _e( 'Expand', 'woocommerce' ); ?></a>
    <a href="#" data-action="close" class="close-all"><?php /* translators: woocommerce */ _e( 'Close', 'woocommerce' ); ?></a>
  </small>
  {{/with}}
  {{#is type 'variation'}}
  <ul class="variant">
    {{#each attributes}}
    <li>
      <strong>{{name}}:</strong> {{#if option}}{{option}}{{else}}{{#list options ', '}}{{this}}{{/list}}{{/if}}
    </li>
    {{/each}}
  </ul>
  {{/is}}
</div>
<div>
  <input type="text" name="stock_quantity" data-label="<?php /* translators: woocommerce */ _e( 'Quantity', 'woocommerce' ); ?>" data-placement="bottom" data-numpad="quantity" class="form-control autogrow"> <label class="small c-input c-checkbox" for="managing_stock[{{id}}]">
    <input type="checkbox" name="managing_stock" id="managing_stock[{{id}}]">
    <span class="c-indicator"></span>
    <?php /* translators: woocommerce */ _e( 'Manage stock?', 'woocommerce' ); ?>
  </label>
</div>
<div>
  {{#is type 'variable'}}
  <span data-name="regular_price"></span> <label class="small c-input c-checkbox" for="taxable[{{id}}]">
    <input type="checkbox" name="taxable" id="taxable[{{id}}]">
    <span class="c-indicator"></span>
    <?php /* translators: woocommerce */ _e( 'Taxable', 'woocommerce' ); ?>
  </label>
  {{else}}
  <input type="text" name="regular_price" data-label="<?php /* translators: woocommerce */ _e('Regular price', 'woocommerce'); ?>" data-placement="bottom" data-numpad="amount" class="form-control autogrow">
    {{#is type 'variation'}}{{else}} <label class="small c-input c-checkbox" for="taxable[{{id}}]">
      <input type="checkbox" name="taxable" id="taxable[{{id}}]">
      <span class="c-indicator"></span>
      <?php /* translators: woocommerce */ _e( 'Taxable', 'woocommerce' ); ?>
    </label>
    {{/is}}
  {{/is}}
</div>
<div>
  {{#is type 'variable'}}
  <span data-name="sale_price"></span>
  {{else}}
  <input type="text" name="sale_price" data-label="<?php /* translators: woocommerce */ _e('Sale price', 'woocommerce'); ?>" data-placement="bottom" data-numpad="amount" class="form-control autogrow"> <label class="small c-input c-checkbox" for="on_sale[{{id}}]">
    <input type="checkbox" name="on_sale" id="on_sale[{{id}}]">
    <span class="c-indicator"></span>
    <?php _e( 'On Sale?', 'woocommerce-pos-pro' ); ?>
  </label>
  {{/is}}
</div>