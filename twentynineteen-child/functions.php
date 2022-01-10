<?php 

//부모 스타일 불러오기
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

function multisite_styles() {
	$current_blog_id = get_current_blog_id();
	if($current_blog_id > 1){
		wp_dequeue_style( 'twentynineteen-style' );
		wp_deregister_style( 'twentynineteen-style' );
		wp_enqueue_style( 'twentynineteen-style-'.$current_blog_id, get_stylesheet_directory_uri() . '/style-'.$current_blog_id.'.css' );
	}
}
add_action( 'wp_print_styles', 'multisite_styles');

//번역을 위한 텍스트도메인
$pather = get_stylesheet_directory().'/languages';
load_child_theme_textdomain( 'twentynineteen-child', $pather );

//우커머스 지원
function theme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'theme_add_woocommerce_support' );

//acf스타일
function acf_print_styles(){
	wp_register_style( 'acf-style', get_stylesheet_directory_uri() . '/acf_style.css');
	wp_enqueue_style('acf-style');
}
add_action('acf/input/admin_enqueue_scripts', 'acf_print_styles');

/*페이지에서 빈 p태그 방지*/
function remove_empty_p(){
	if(is_page()){
		remove_filter('the_content', 'wpautop');
	}
}
add_action('wp_head', 'remove_empty_p');

function define_customlogo() {
	add_theme_support( 'custom-logo', array(
			'width'       => 202,
			'height'      => 48,
	) );
}
add_action( 'after_setup_theme', 'define_customlogo', 11 );

function twentynineteen_dequeue_scripts() {
	wp_dequeue_script( 'twentynineteen-priority-menu' );
	wp_deregister_script( 'twentynineteen-priority-menu' );
	wp_dequeue_script( 'twentynineteen-touch-navigation' );
	wp_deregister_script( 'twentynineteen-touch-navigation' );
}
add_action( 'wp_print_scripts', 'twentynineteen_dequeue_scripts', 100 );


//상품만 검색하기
add_action( 'pre_get_posts', 'search_woocommerce_only' );
function search_woocommerce_only( $query ) {
	if( is_search() && $query->is_main_query() ) {
		if(!isset($_GET['s']) || empty($_GET['s'])){
			$query->set_404();
		}
		else{
			$query->set( 'post_type', 'product' );
		}
  	}
}

//include ('submenu-nav.php');
include ('woocommerce-functions.php');
include ('woocommerce-product-addon.php');
include ('woocommerce-product-addon-action.php');
//include ('woocommerce-copy-addon.php');

//메뉴 위치 추가
function register_info_menu() {
	register_nav_menu('info-menu',__( 'Info  Menu' ));
}
add_action( 'init', 'register_info_menu' );

function temp_style(){
	?>
	<style>
		/*.shop-mega-menu.show-menu{display:block;}*/
		/*.shop-mega-menu:hover{display:block;}*/
	</style>
	<?php
	}
	add_action('wp_head', 'temp_style');

function temp_megamenu_script(){
?>
<script type="text/javascript" async="async">
(function($){
	$('.menu-item-shop>a').hover(
		function(e){
			e.stopPropagation();
			//$('.shop-mega-menu').addClass('show-menu');
			$('.shop-mega-menu').slideDown();
		}/*,
		function(e){
			e.stopPropagation();
			//$('.shop-mega-menu').removeClass('show-menu');
			$('.shop-mega-menu').slideUp();
		}*/
	);

	$('.shop-mega-menu').hover(
		function(e){
			e.stopPropagation();
			$('.shop-mega-menu').addClass('show-menu');
		},
		function(e){
			e.stopPropagation();
			$('.shop-mega-menu').removeClass('show-menu').slideUp();
		}
	);
	
})(jQuery);
</script>
<?php
}
add_action('wp_footer', 'temp_megamenu_script');

