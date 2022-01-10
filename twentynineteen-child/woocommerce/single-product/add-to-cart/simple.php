<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

$is_set_addon=get_post_meta( $post->ID, '_smtp_prd_addon_set', true );

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input( array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		) );

		do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>
		<?php if(!$is_set_addon):?>
		<div class="single-price-wrap">
			<span class="woocommerce-Price-amount amount">
				<span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol();?></span>
				<span class="woocommerce-price"><?php echo number_format($product->get_price(), 2);?></span>
			</span>
		</div>
		<script type="text/javascript">
		(function($){
			$('input.qty').on('input', function(){
				var prdPriceWrap=$('.single-price-wrap .woocommerce-price');
				var decimals=<?php echo wc_get_price_decimals(); //상품가격 소숫점?>;
				var prdPrice=<?php echo $product->get_price(); //상품 가격?>;
				var currentQty=Number($(this).val());
				var calResult= parseFloat(prdPrice*currentQty).toFixed(decimals);
				console.log(calResult);
				prdPriceWrap.html($.number(calResult, decimals));
			});
		})(jQuery);
		</script>
		<?php endif;?>
		<div class="single-cart-wrap">
		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
		<?php echo do_shortcode('[wc_quick_buy  label="'.__('Buy Now', 'twentynineteen-child').'" product="'.esc_attr( $product->get_id() ).'"]')?>
		</div>
		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
