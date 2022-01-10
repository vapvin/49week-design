<?php

function submenu_shortcode(){
	$home_url = '<a class="submenu-home" href="'.home_url('/').'">'.__('홈').'</a>';
	$parents = get_post_ancestors( $post->ID );
	$parents = array_reverse($parents);
	ob_start();
	
	$highest_level='';
	$children='';
	$submenu_content='';
	
	//최상위메뉴
	$highest_level.='<div class="submenu-container highest-submenu-dropdown submenu-dropdown">';
	$highest_level.='<div class="current-highest-page-title current-page-title"></div>';
	$highest_level.=wp_nav_menu(array(
			'menu' => 'Main Menu',
			'menu_class' => 'highest-submenu-dropdown-ul submenu-dropdown-ul',
			'container' => '',
			'depth' => 1,
			'echo' => 0
	));
	$highest_level.='</div>';	//highest-submenu-dropdown
	
	if($parents){
		foreach ($parents as $parent => $parent_id){
			$children.='<div class="submenu-container submenu-dropdown">';
			$children.='<div class="current-page-title"></div>';
			$children.='<ul class="submenu-dropdown-ul submenu-'.$parent.'">';
			$children.=wp_list_pages(array(
					'child_of'	=>	$parent_id,
					'depth' => 1,
					'title_li'	=>	'',
					'echo' => 0
			));
			$children.='</ul>';	//submenu-dropdown-ul
			$children.='</div>';	//submenu-dropdown
			
		}
	}
	
	//style
	$submenu_content.='<style type="text/css">';
	$submenu_content.='#submenu-list{display: -webkit-flex; display: -ms-flexbox; display: flex; -webkit-align-items: center; -ms-flex-align: center; align-items: center; width:100%; height:50px;}
	#submenu-list div{height:100%; line-height: 50px;}
	.submenu-container{display:inline-block;}
	.highest-submenu-dropdown-ul{position:absolute; display:none; list-style:none; margin:0; z-index:999;}
	.current-highest-page-title{cursor:pointer;}
	.submenu-dropdown-ul{position:absolute; display:none; list-style:none; margin:0; z-index:999;}
	.current-page-title{cursor:pointer;}
	.nav-arrow{float:right;}
	/*.main-menu-more{display:none;}
	button.submenu-expand{display:none;}*/';
	$submenu_content.='</style>';
	
	//submenu start
	$submenu_content.='<aside id="submenu-list">';
	$submenu_content.='<div class="submenu-container go-home">
							<a href="'.home_url().'"><img src="'.get_stylesheet_directory_uri().'/nav-img/nav_home.png" alt="home"></a>
						</div>';
	$submenu_content.=$highest_level;
	
	if($children){
		$submenu_content.=$children;
	}
	$submenu_content.='</aside>';
	
	return $submenu_content;

	ob_end_clean();
}
add_shortcode('page_submenu', 'submenu_shortcode');

function submenu_script(){
?>
<script>
(function($){
	$('.main-menu-more').remove();
	$('button.submenu-expand').remove();
	
	var navArrow='<span class="nav-arrow"><img src="<?php echo get_stylesheet_directory_uri();?>/nav-img/nav_arrow.png"></span>';

	/*$('.highest-submenu-dropdown').each(function(){
		var currentItemText=$(this).find('.current-menu-ancestor, .current_page_item').text();
		$(this).find('.current-highest-page-title').text(currentItemText).append(navArrow);
	});*/

	$('.submenu-dropdown').each(function(){
		var currentItemText=$(this).find('.current-page-ancestor, .current_page_ancestor, .current_page_item').text();
		$(this).find('.current-page-title').text(currentItemText).append(navArrow);
	});
	
	var allDropdown=$('.submenu-dropdown-ul').hide();
	
	$('.current-page-title').on('click', function(){
		var currentDropdown=$(this).siblings('.submenu-dropdown-ul');
		allDropdown.slideUp();
		allDropdown.not(currentDropdown).removeClass('on');
		currentDropdown.toggleClass('on');

		if(currentDropdown.hasClass('on')==true){
			currentDropdown.slideDown();
		}
		else{
			currentDropdown.removeClass('on')
			currentDropdown.slideUp();
		}
	});
})(jQuery);
</script>
<?php
}
add_action('wp_footer', 'submenu_script');