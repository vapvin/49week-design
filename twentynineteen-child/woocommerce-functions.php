<?php

function scripts_for_woocommerce(){
	wp_enqueue_script( 'jquery-number', get_stylesheet_directory_uri() . '/jquery.number.min.js', array( 'jquery' ) );	//제이쿼리 숫자 포맷
}
add_action( 'wp_enqueue_scripts', 'scripts_for_woocommerce' );

//통화기호 변경
add_filter('woocommerce_currency_symbol', 'change_currency_symbol', 10, 2);
function change_currency_symbol($currency_symbol, $currency){
    switch( $currency ) {
        case 'USD':
            $currency_symbol = 'US$';
        break;
    }
    return $currency_symbol;
}

/*** 내 계정 ***/
//다른 로그인 페이지로 리다이렉트
function my_account_login_redirect(){
    $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $dashboard_url = get_permalink( get_option('woocommerce_myaccount_page_id'));

    if(!is_user_logged_in() && $dashboard_url == $current_url){
        $url = get_home_url() . '/login';
        wp_redirect( $url );
        exit;
    }
}
add_action('template_redirect', 'my_account_login_redirect');

//바로 로그아웃하기
function bypass_logout_confirmation() {
	global $wp;

	if ( isset( $wp->query_vars['customer-logout'] ) ) {
		wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );
		exit;
	}
}
add_action( 'template_redirect', 'bypass_logout_confirmation' );


/*** 상품 목록 ***/

/** 상품 rollover 추가 **/
/*function before_shop_loop_item_title(){
?>
<div class="prd-contents">
    <div class="prd-contents-inner">
<?php
}
add_action('woocommerce_before_shop_loop_item_title', 'before_shop_loop_item_title', 10);

function after_shop_loop_item_title(){
?>
    </div><!--prd-contents-inner-->
</div><!--prd-contents-->
<?php
}
add_action('woocommerce_after_shop_loop_item_title', 'after_shop_loop_item_title', 11);*/
/** 상품 rollover 끝 **/

//상품간략설명
/*function woocommerce_after_shop_loop_item_title_short_description() {
	global $product;
    if ( ! $product->get_short_description() ) return;
	?>
	<div class="short-description">
		<?php echo apply_filters( 'woocommerce_short_description', mb_strimwidth($product->get_short_description(),0,40,'…','utf-8') ) ?>
	</div>
	<?php
}
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_after_shop_loop_item_title_short_description', 10);*/

//별점 삭제
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

//장바구니 버튼 삭제
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

//리뷰 개수 표시
function product_list_review_count(){
    global $product;
    
    if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
        return;
    }

    $review_count = $product->get_review_count();
?>
<div class="review-count">
    <p><span class="review-ico"><i class="fa fa-heart" aria-hidden="true"></i><span>reviews : <span class="count"><?php echo $review_count?><span></p>
</div>
<?php
}
add_action('woocommerce_after_shop_loop_item_title', 'product_list_review_count', 5);

//갤러리 이미지 1개 불러오기
function woocommerce_template_loop_product_gallery(){
    global $product;

    $image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );

    $gallery_image_ids = $product->get_gallery_image_ids();
    $one_gallery_image_id = '';
    $one_gallery_image = '';
    
    if(count($gallery_image_ids)>0){
    	$one_gallery_image_id = $gallery_image_ids[0];
    	$one_gallery_image = '<div class="prd-gallery-img">'.wp_get_attachment_image( $one_gallery_image_id, $image_size ).'</div>';
    }
    
    return $one_gallery_image;
}

//상품목록 썸네일 출력 재정의
if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {

	/**
	 * Get the product thumbnail for the loop.
	 */
	function woocommerce_template_loop_product_thumbnail() {
        $prd_thumbnails='<div class="prd-thumbnails-wrap">'.
        '<div class="prd-tumbnail">'.
        woocommerce_get_product_thumbnail().
        '</div>'.   //prd-tumbnail
        woocommerce_template_loop_product_gallery(). //prd-gallery-img
        '</div>';   //prd-thumbnails-wrap

		echo $prd_thumbnails; // WPCS: XSS ok.
	}
}


/*** 상품 상세 ***/
//줌 기능 삭제
remove_theme_support( 'wc-product-gallery-zoom' );

//하단 슬라이드 스크립트 추가
function swiper_slider(){
	wp_enqueue_style( 'swiper-style', get_stylesheet_directory_uri() . '/swiper/swiper.min.css');
	wp_enqueue_script( 'swiper-script', get_stylesheet_directory_uri() . '/swiper/swiper.min.js', array(), '4.4.6', true);
}
add_action('wp_enqueue_scripts', 'swiper_slider');

function init_swiper(){
	?>
<script type="text/javascript">
(function($){
    $(document).ready(function(){
        var appSliderNav = new Swiper('.app-slider-nav', {
            spaceBetween: 10,
            slidesPerView: 3,
            slidesPerColumn: 2,
         //   freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
        var appSlider = new Swiper('.app-slider', {
            slidesPerView: 1,
            effect: 'fade',
            thumbs: {
                swiper: appSliderNav
            }
        });
    });
	
	
})(jQuery);
</script>
<?php
}
add_action('wp_footer', 'init_swiper');

/*** 장바구니 ***/

/** 장바구니 메뉴 **/
add_shortcode ('woo_cart_but', 'woo_cart_but' );
function woo_cart_but() {
	ob_start();
    $cart_count = WC()->cart->cart_contents_count; //장바구니 물건 개수
    $cart_url = wc_get_cart_url();  //장바구니 url
?>
<a class="menu-item cart-contents" href="<?php echo $cart_url; ?>"><?php echo __('Cart', 'woocommerce');?>
<?php if ( $cart_count > 0 ): ?><span class="cart-contents-count">(<?php echo $cart_count; ?>)</span><?php endif;?>
</a>
<?php        
    return ob_get_clean();
 
}

/** 상품 무게 계산 **/
//상품 무게 반올림
add_filter('woocommerce_cart_contents_weight', 'round_cart_contents_weight');
function round_cart_contents_weight($weight) {
    return round($weight);
}
//상품 무게 장바구니에 표시
add_action('woocommerce_before_checkout_form', 'print_cart_weight');
add_action('woocommerce_before_cart', 'print_cart_weight');
function print_cart_weight( $posted ) {
    global $woocommerce;
    $notice = __('Total weight: ', 'twentynineteen-child') . number_format($woocommerce->cart->cart_contents_weight) . get_option('woocommerce_weight_unit');
    if( is_cart() ) {
        wc_print_notice( $notice, 'notice' );
    } else {
        wc_add_notice( $notice, 'notice' );
    }
}

/**장바구니 비우기**/
add_action('init', 'woocommerce_clear_cart_url');
function woocommerce_clear_cart_url() {
	global $woocommerce;
	if( isset($_REQUEST['clear_cart']) ) {
		$woocommerce->cart->empty_cart();
	}
}

/**무료배송일때 다른 배송정책 감추기**/
function hide_shipping_when_free_is_available( $rates ) {
	$free = array();
	$current_currency= get_woocommerce_currency();	//현재 쇼핑몰의 통화
	
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	
	if($current_currency == 'KRW'){	//한국 원일때
		return ! empty( $free ) ? $free : $rates;
	}
	else {
		return $rates;
	}
}
add_filter( 'woocommerce_package_rates', 'hide_shipping_when_free_is_available', 100 );

/*** 결제 ***/
add_filter('woocommerce_billing_fields', 'custom_billing_fields', 999, 1);
function custom_billing_fields( $fields ) {
	$fields['billing_phone']['required'] = true;	//전화번호 필수
	
	return $fields;
}
