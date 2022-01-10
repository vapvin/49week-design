<?php

function woocommerce_product_custom_fields () {
	global $woocommerce, $post;
	
	// Get the selected value  <== <== (updated)
	$selected_value= get_post_meta( $post->ID, '_smtp_prd_addon_set', true );
	if( empty( $selected_value ) ) $selected_value= '';
	
	//상품옵션리스트 불러오기
	$prd_addon_args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'cat'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'product_add_on',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'author'	   => '',
			'author_name'	   => '',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'fields'           => '',
	);
	
	$get_prd_addons = get_posts($prd_addon_args);
	$prd_addon_list = array();
	$prd_addon_list['']=__( 'Select a value', 'twentynineteen-child');
	
	foreach ( $get_prd_addons as $prd_addon ) : setup_postdata( $prd_addon );
	$prd_addon_list[$prd_addon->ID]=get_the_title($prd_addon);
	endforeach;
	
	wp_reset_postdata(); //옵션리스트 끝
	
	echo '<div class="sun-prd-addon-setting">';
	$args = array(
			'label' => __('SMTP 옵션 선택', 'twentynineteen-child'), // Text in Label
			'class' => 'sun-prd-addon-list',
			'style' => '',
			'wrapper_class' => '',
			'value' => $selected_value, // if empty, retrieved from post meta where id is the meta_key
			'id' => '_smtp_prd_addon_set', // required
			'options' => $prd_addon_list, // Options for select, array
			'desc_tip' => '',
			'custom_attributes' => '', // array of attributes
			'description' => ''
	);
	woocommerce_wp_select( $args );
	echo '</div>';
}
add_action( 'woocommerce_product_options_inventory_product_data', 'woocommerce_product_custom_fields' );

function woocommerce_product_custom_fields_save($post_id){
	// WooCommerce custom dropdown Select
	$woocommerce_custom_product_select = $_POST['_smtp_prd_addon_set'];
	if (!empty($woocommerce_custom_product_select)){
		update_post_meta($post_id, '_smtp_prd_addon_set', esc_attr($woocommerce_custom_product_select));
	}
	else{
		update_post_meta( $post_id, '_smtp_prd_addon_set',  '' );
	}
	
}
add_action( 'woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save' );

//개별 판매일때만 옵션 선택하기
function woocommerce_product_custom_fields_scripts($hook) {
	global $post;
	$screen = get_current_screen();
	
	if ( $screen->action == 'add' || $screen->base == 'post' ):
		if ( 'product' === $post->post_type ) :
?>
<script type="text/javascript">
(function($){
	var addOnSetting = $('.sun-prd-addon-setting');
	var soldIndv = $('#_sold_individually');

	if(soldIndv.is(':checked') == true){
		addOnSetting.show();
	}
	else{
		addOnSetting.hide();
	}

	soldIndv.on('change', function(){
		if($(this).is(':checked') == true){
			addOnSetting.show();
		}
		else{
			addOnSetting.hide();
			$('#_smtp_prd_addon_set option[value=""]').prop('selected', true);
		}
	});
})(jQuery);
</script>
<?php 
		endif;
	endif;
}
add_action( 'admin_footer', 'woocommerce_product_custom_fields_scripts');

/**어드민 바에 상품옵션 수정 링크 추가**/
add_action('admin_bar_menu', 'add_toolbar_items', 100);
function add_toolbar_items($admin_bar){
	global $post;
	
	$is_set_addon=get_post_meta( $post->ID, '_smtp_prd_addon_set', true );
	
	if(is_product() && $is_set_addon){
		$admin_bar->add_menu( array(
				'id'    => 'edit-prd-addon',
				'title' => __('상품 옵션 편집', 'twentynineteen-child'),
				'href'  => get_edit_post_link($is_set_addon),
		));
	}
}

/**
 * @snippet       Add input field to products - WooCommerce
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.5.7
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

// -----------------------------------------
// 1. Show custom input field above Add to Cart

add_action( 'woocommerce_before_add_to_cart_button', 'bbloomer_product_add_on', 9 );

function bbloomer_product_add_on() {
	global $woocommerce, $post;
	
	$is_set_addon=get_post_meta( $post->ID, '_smtp_prd_addon_set', true );
	
	if(!empty($is_set_addon)):
	$prd_option_setting_rows = get_field('prd_option_setting', $is_set_addon);
	
	
	if($prd_option_setting_rows):
	?>
<div class="sun-prd-addons">
	<div class="sun-prd-addons-wrap">
	<input type="hidden" name="prd_id" value="<?php echo $post->ID;?>" class="js-sun-pid">
	<input type="hidden" name="prd_addon_id" value="<?php echo $is_set_addon;?>" class="js-sun-prd-addon">
	<?php while(has_sub_field('prd_option_setting', $is_set_addon)):
		//옵션 타입
		$option_type = get_sub_field('option_type');
		
		//옵션 이름
		$option_name = get_sub_field('option_name');
		$option_name_label = $option_name['option_name_label'];
		$option_name_value = $option_name['option_name_value'];
		$option_name_display = $option_name['option_name_display'];
		
		//옵션 설명
		$option_description = get_sub_field('option_description');
		
		//서브옵션 불러오기
		$sub_options = get_sub_field('sub_options');
		$sub_options_count = count($sub_options); //서브옵션 개수
		
		//계산 할 필드
		$input_tobe_calculate = get_sub_field('input_tobe_calculate');
		
		//합계 단위 텍스트
		$total_unit_text = get_sub_field('total_unit_text');

		$sun_addon_number=get_row_index(); //옵션번호
		$matrix_col_index = 0;
		
		if($option_type == 'section'): //옵션명?>
        <div class="sun-prd-addon-name sun-prd-addon-section">
            <h3><?php echo $option_name_label;?></h3>
            <p><?php echo $option_description;?></p>
        </div>
        <?php elseif($option_name_display == true):?>
        <p class="sun-prd-addon-name sun-addon-name-<?php echo $option_type;?> sun-addon-name-val-<?php echo $option_name_value;?>"><?php echo $option_name_label;?></p>
        <?php endif;?>
        
		<?php if($sub_options && ($option_type != 'section')): //서브옵션시작?>
			<?php if($option_type == 'matrix'): //계산폼 헤더 ?>
			<div id="sun-addon-<?php echo $sun_addon_number;?>" class="sun-prd-addon-suboptions sun-prd-addon-matrix <?php echo $input_tobe_calculate?>" data-opt-label="<?php echo esc_attr($option_name_label);?>" data-opt-name="<?php echo esc_attr($option_name_value);?>">
                <div class="matrix-row matrix-header">
                    <div class="sun-checked-option matrix-col first-col">
                        <div></div>
                    </div>
            <?php elseif($option_type == 'image_select'):?>
            <div id="sun-addon-<?php echo $sun_addon_number;?>" class="sun-prd-addon-suboptions sun-<?php echo $option_type?>">
            	<div class="sun-hover-img-tooltip">
            		<h3 class="sun-hover-img-tooltip-title"><?php echo __('Detail View', 'tewntynineteen-child')?></h3>
            		<div class="sun-hover-img-tooltip-content"></div>
            	</div>
            	<script type="text/javascript">
            	(function($){
                	$(document).ready(function(){
                		var currentAddon=$('#sun-addon-<?php echo $sun_addon_number;?>');
                		var currentAddonInput=$('#sun-addon-<?php echo $sun_addon_number;?> label');
                		var currentHoverImg;
                		var currentHoverImgTooltip=$('#sun-addon-<?php echo $sun_addon_number;?> .sun-hover-img-tooltip');
						var currentHoverImgTooltipContent=$('#sun-addon-<?php echo $sun_addon_number;?> .sun-hover-img-tooltip-content');

						currentHoverImgTooltip.hide();
						
                		currentAddonInput.on('mouseenter', function(e){
                    		e.stopPropagation();
                    		currentHoverImgTooltip.appendTo($(this)).show();
                    		currentHoverImg='<img src="'+$(this).find('img').attr('src')+'">';
                    		currentHoverImgTooltipContent.html(currentHoverImg);

                    		$(this)
                    	}).on('mousemove', function(e){
                    		e.stopPropagation();
                			currentHoverImgTooltip.css('top', e.clientY+10);
                			currentHoverImgTooltip.css('left', e.clientX+10);
                    	}).on('mouseleave', function(e){
                    		e.stopPropagation();
                			currentHoverImgTooltip.hide();
                    	});
                    	
                    });
                })(jQuery);
            	</script>
			<?php else:?>
			<div id="sun-addon-<?php echo $sun_addon_number;?>" class="sun-prd-addon-suboptions">
			<?php endif;?>
			<?php while(has_sub_field('sub_options')):
				$sub_option_name = get_sub_field('sub_option_name');	//옵션명
				$sub_option_image = get_sub_field('sub_option_image');	//옵션이미지
				$price_rate = get_sub_field('price_rate');	//가격,수치
				$length = get_sub_field('length');
				$weight = get_sub_field('weight');
			?>
				<?php if($option_type == 'radio_btn'):?>
				<label class="sun-input-container sun-input-radio">
					<input type="radio" name="<?php echo $option_name_value; ?>" value="<?php echo esc_attr($sub_option_name);?>" class="<?php echo $option_name_value;?>" data-required-field="">
					<div class="sun-input-label-text"><?php echo $sub_option_name; ?></div>
				</label>
				<?php elseif($option_type == 'checkbox'):?>
				<label class="sun-input-container sun-input-checkbox">
					<input type="checkbox" name="<?php echo $option_name_value; ?>" value="<?php echo esc_attr($sub_option_name); ?>" class="<?php echo $option_name_value;?>" >
					<div class="sun-input-label-text"><?php echo $sub_option_name; ?></div>
				</label>
				<?php elseif($option_type == 'image_select'):
						$size = 'full';
						$img_url = $sub_option_image['url'];
				?>
				<label class="sun-img-checkbox sun-input-container">
					<input type="checkbox" name="<?php echo $option_name_value; ?>" value="<?php echo esc_attr($sub_option_name); ?>" class="<?php echo $option_name_value;?>">
					<img src="<?php echo $img_url?>" alt="<?php echo $option_name_value; ?>">
					<div class="sun-tooltip-text"><?php echo $sub_option_name; ?></div>
					<div class="sun-input-inner-container"></div>
				</label>
					
				<?php elseif($option_type == 'matrix'):?>
					<div class="matrix-col matrix-quantity" data-col-index="<?php echo $matrix_col_index++;?>" data-col-sub-opt-name="<?php echo esc_attr($sub_option_name);?>">
						<div><?php echo $sub_option_name; ?></div>
						<div class="matrix-col-price"><?php echo wc_price($price_rate);?></div>
						<input type="hidden"  name="sun_matrix_prd_rate" value="<?php echo $length; ?>" class="<?php echo $option_name_value;?>">
					</div>
				<?php endif;?>
			<?php endwhile;?>
			<?php if($option_type == 'matrix'): //계산폼 합계 타이틀?>
                    <div class="matrix-col total-title">
                        <div><?php echo __('TOTAL'); echo $total_unit_text?' '.$total_unit_text:''; ?></div>
                    </div>
                </div><!-- end matrix row-->
            <?php endif;?>
			</div><!-- sun-prd-addon-suboptions -->
		<?php endif; //$sub_options
				
		/**conditional logic**/		
		$conditional_logic = get_sub_field('conditional_logic');
		$use_conditional_logic = $conditional_logic['use_conditional_logic'];
		if($use_conditional_logic=='yes'):
		
		
			$show_field='show-field';
			$add_conditional_logic = $conditional_logic['add_conditional_logic'][0];
			$conditional_if_field=$add_conditional_logic['if_field'];
			$conditional_is_field=$add_conditional_logic['is_field'];
			$equal_field_value=$add_conditional_logic['equal_field_value'];
		//	var_dump($add_conditional_logic);
		//echo $conditional_logic['if_field'];
		?>
		<script type="text/javascript">
		(function($){
			var currentAddon=$('#sun-addon-<?php echo $sun_addon_number;?>');
			var conditional_if_field='<?php echo $conditional_if_field;?>';
			var conditional_is_field='<?php echo $conditional_is_field;?>';
			var equal_field_value='<?php echo $equal_field_value;?>';
			
			//Radio Btn
			var firstInputRadio = $('.sun-input-radio:first-child input');
			var firstInputRadioClass = '.'+firstInputRadio.attr('class'); //첫번째라디오 클래스
			//var firstInputRadioVal = firstInputRadio.val();
			firstInputRadio.prop('checked', true); //첫번째라디오 체크
			
			currentAddon.hide().removeClass('show-addon'); //모든옵션 숨기기
			console.log(currentAddon);
			firstInputRadio.each(function(){
				if($(this).val() == equal_field_value){ //첫번째 라디오 옵션 보이기
					currentAddon.show().addClass('show-addon');
				}
			});
			
			
			$('input[name="'+conditional_if_field+'"]').on('change', function(){
				//console.log($(this).val());
				if($(this).val() == equal_field_value){
					currentAddon.show().addClass('show-addon');
				//	console.log(currentAddon);
				}
				else{
					currentAddon.hide().removeClass('show-addon');
				}
			});
			
//			console.log(currentAddon);
		})(jQuery);
		</script>
		<?php endif; //end conditional logic?>
		
		<?php if($option_type == 'matrix'):?>
		<script type="text/javascript">
		(function($){
			var addonNumber='#sun-addon-<?php echo $sun_addon_number;?>'
			var tmpChkSuboption=[];
			var inputToBeCal='.<?php echo $input_tobe_calculate?>'; //계산 기준이 되는 옵션
			var matrixOptionsCount=<?php echo $sub_options_count?>;
			
			$('input'+inputToBeCal+'[type="checkbox"]').on('change', function(){
				var isChecked=$(this).is(':checked');
				var suboptionImg=$(this).siblings('img').attr('src');
				var suboptionValue=$(this).attr("value");
				var matrixColValue=$(inputToBeCal+' .matrix-quantity');
				var checkedOptionRow=$(addonNumber+inputToBeCal);
				
				if(isChecked){
					tmpChkSuboption.push({img: suboptionImg, value: suboptionValue});
					
					var matrixRow = $('<div class="matrix-row matrix-dynamic-row"  data-matrix-class="'+suboptionValue+'"></div>');
					
					var totalField = '<div class="matrix-col total-field"><input class="matrix-quantity-total-field" type="number" name="sun_prd_qty" readonly></div>'
						
					matrixRow.append('<div class="matrix-col first-col"><div><img src="'+suboptionImg+'"><span>'+suboptionValue+'</span></div></div>');
					
					for(var i=0; i<matrixOptionsCount; i++){
						var matrixCol = '<div class="matrix-col value-col" data-col-index="'+i+'"><input class="matrix-quantity-field" type="number" name="sun_prd_qty" min="1" step="1" data-selected-value="'+suboptionValue+'" data-prd-measure=""></div>';
						matrixRow.append(matrixCol);
					}
					matrixRow.append(totalField);
					
					checkedOptionRow.append(matrixRow);
				}
				else{
					tmpChkSuboption.splice($.inArray({img: suboptionImg, value: suboptionValue}, tmpChkSuboption),1);
					//matrixColValue.each(function(){
					//	$('div').remove('[data-matrix-class="'+suboptionValue+'"]');
					//});
					
					var removeRow=$('[data-matrix-class="'+suboptionValue+'"]');
					remove_unchecked_addon(removeRow);
//					console.log(checkedOptionRow.find('.matrix-row').length);
				}

				if(checkedOptionRow.find('.matrix-row').length > 1){
					checkedOptionRow.find('.matrix-header').addClass('show-field');
				}
				else{
					checkedOptionRow.find('.matrix-header').removeClass('show-field');
				}
			});

			function remove_unchecked_addon(selectedRow){
				var currentMatrixId='#'+$(this).parents('.sun-prd-addon-matrix').attr('id');
				
				//var getMatrixRow=$(this).parents('.matrix-row');	//현재 matrix row
				var getMatrixRow = selectedRow;
				var matrixClass=getMatrixRow.data('matrix-class');	//현재 선택된 옵션값
				var thisColIndex=$(this).parent().data('col-index');
				var thisOptionName=$(currentMatrixId).data('opt-name');
				var thisOptionLabel=$(currentMatrixId).data('opt-label');
				var thisSubOptionName=$(currentMatrixId+' .matrix-header .matrix-col[data-col-index="'+thisColIndex+'"]').data('col-sub-opt-name');
			//	console.log(currentMatrixId);
			//	console.log(getMatrixRow);
			//	var currentValQty=$(this).val();
				var matrixRowData={/*option_name:thisOptionName, option_label:thisOptionLabel,*/ matrix_class:matrixClass, action:'remove'/*, option_value:thisSubOptionName, qty:currentValQty*/};

				getMatrixRow.find('input[name="sun_prd_qty"]').eq(0).trigger('input', [{data: [matrixClass]}]);
			}

			//Search Object exist
		})(jQuery);
		</script>
		<?php endif;?>
	<?php endwhile;?>
	</div><!-- end add on inner -->
	<div class="sun-order-summary-wrap">
		<h3  class="sun-order-summary-title"><?php echo __('Order Summary', 'twentynineteen-child')?></h3>
		<div class="sun-order-summary">
			<div class="sun-order-summary-list"></div>
			<div class="sun-order-summary-totalprice"></div>
		</div>
	</div>
</div><!-- end add on -->
<div id="reset-all-addons"><span><?php echo __('Reset All', 'twentynineteen-child')?></span></div>
<script type="text/javascript">
(function($){
	$(document).ready(function(){
		//cart validation
		$('button[name="add-to-cart"]').prop('disabled', true);
		$('input.wc_quick_buy_button').prop('disabled', true);
	});
})(jQuery);
</script>
<?php
		endif;	//$rows
	endif;	//$is_set_addon
}

add_action( 'woocommerce_after_add_to_cart_button', 'sun_calculate_price', 9 );
function sun_calculate_price(){
	$ajax_nonce = wp_create_nonce( "sun-prd-addon-secure" );
	?>
<script type="text/javascript">
(function($){
	var prdAddonRow=[];
	$(document).on('input', 'input[name="sun_prd_qty"]', function(event, param){
		event.stopPropagation();
		if(param!=undefined){
			var removeMatrixRow=param.data[0];
		}
//		console.log(removeMatrixRow);
		var currentMatrixId='#'+$(this).parents('.sun-prd-addon-matrix').attr('id'); //현재 매트릭스 id
		
		var getMatrixRow=$(this).parents('.matrix-row');	//현재 matrix row
		var matrixClass=getMatrixRow.data('matrix-class');	//현재 선택된 옵션값
		var thisColIndex=$(this).parent().data('col-index');	//현재 col 인덱스
		var thisOptionName=$(currentMatrixId).data('opt-name');//현재 옵션 네임
		var thisOptionLabel=$(currentMatrixId).data('opt-label');//현재 옵션 라벨
		var thisSubOptionName=$(currentMatrixId+' .matrix-header .matrix-col[data-col-index="'+thisColIndex+'"]').data('col-sub-opt-name');
		
		var currentValQty=$(this).val();	//현재 입력받은 값
		var matrixRowData={option_name:thisOptionName, option_label:thisOptionLabel, matrix_class:matrixClass, option_value:thisSubOptionName, qty:currentValQty};
	//	console.log($('.matrix-header .matrix-col[data-col-index="'+thisColIndex+'"]'));
		//get Array index
		var sameRowEl=getMatrixRow.find('input.matrix-quantity-field');
		var sameRowTotalField=getMatrixRow.find('input.matrix-quantity-total-field');
		var sameRowValue=[];
		var thisMatrixValue=[];
		var sameRowColIndex;
		var totalRate = 0;

		var sameRowColIndex = 0;

		var searchAddonObj;
		
		sameRowEl.each(function(){
			sameRowValue[sameRowColIndex]=$(this).val();
			thisMatrixValue[sameRowColIndex]=$(currentMatrixId+' .matrix-header .matrix-col[data-col-index="'+sameRowColIndex+'"] input').val();
			totalRate += Number(sameRowValue[sameRowColIndex]) * Number(thisMatrixValue[sameRowColIndex]);
			sameRowColIndex+=1;
		});
		sameRowTotalField.val(totalRate);

		if(removeMatrixRow!=undefined && removeMatrixRow!=''){ //옵션삭제
			var grepRemoveAddonRow = $.grep(prdAddonRow, function (e) {
			    return e.matrix_class === removeMatrixRow;
			});
			
			for(var i=0; i<grepRemoveAddonRow.length; i++){
				searchAddonObj = arrayFind(prdAddonRow, function(v){
				    return (v.matrix_class === grepRemoveAddonRow[i].matrix_class) && (v.option_name === grepRemoveAddonRow[i].option_name) && (v.option_value === grepRemoveAddonRow[i].option_value);
				});
				prdAddonRow.splice(searchAddonObj, 1);
			}

			$('div').remove('[data-matrix-class="'+removeMatrixRow+'"]')
	//		console.log(grepRemoveAddonRow);
		}
		else{
			
			searchAddonObj = arrayFind(prdAddonRow, function(v){
			    return (v.matrix_class === matrixRowData.matrix_class) && (v.option_name === matrixRowData.option_name) && (v.option_value === matrixRowData.option_value);
			});

	//		console.log('searchAddonObj:'+searchAddonObj);
	//		console.log(matrixRowData);
			if((currentValQty!='')){
				
				if(searchAddonObj==-1){
					prdAddonRow.push(matrixRowData);
				}
				else if(searchAddonObj > -1){
					prdAddonRow.splice(searchAddonObj, 1);
					prdAddonRow.push(matrixRowData);
					//console.log(prdAddonRow[searchAddonObj]);
				}
			}
			else{
				if(searchAddonObj>-1){
				//	console.log(prdAddonRow[searchAddonObj]);
					prdAddonRow.splice(searchAddonObj, 1);
				}
			}
		}
		console.log(prdAddonRow);
		
		var  dataObj = {};
		var prdId=$('input[name="prd_id"]').val();
		var prdAddonId=$('input[name="prd_addon_id"]').val();
		
		sun_calculate_price(prdId, prdAddonId, prdAddonRow);
	});

	//Search Object exist
	function arrayFind(arr, fn) {
	    for( var i = 0, len = arr.length; i < len; ++i ) {
	        if( fn(arr[i]) ) {
	            return i;
	        }
	    }
	    return -1;
	}

	
	function sun_calculate_price(prdId, prdAddonId, fields){
		var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
		var data = {
	            action: 'sun_prd_price_calc',
	            security: '<?php echo $ajax_nonce; ?>',
	            prd_id: prdId,
	            prd_addon_id: prdAddonId,
	            data: fields,
	            dataType: 'json',
	        };
		$.ajax({
			url: ajaxurl,
			type: 'post',
			dataType: 'json',
			data: data,
			success:function(response){
				sun_print_order_summary(response);
			},
			error:function(){
				alert('error');
			}
		});
		
	}

	function sun_print_order_summary(response){
		//console.log(response);
		var orderSummaryList=$('.sun-order-summary-list');
		var orderListTitle=$('<h3 class="order-list-title"></h3>');
		var orderSummaryTotalPrice=$('.sun-order-summary-totalprice');
		var orderSummaryListTable;
		
		var mainKeys;
		var subkeys;

		var addToCartBtn=$('button[name="add-to-cart"]');
		var buyNowBtn=$('input.wc_quick_buy_button');
		
		if(response == 0){	//장바구니 유효성
			addToCartBtn.prop('disabled', true);
			buyNowBtn.prop('disabled', true);
		}
		else{
			mainKeys = Object.keys(response);
			addToCartBtn.prop('disabled', false);
			buyNowBtn.prop('disabled', false);
		}
		
		
		orderSummaryList.html('');
		orderListTitle.html('');
		orderSummaryTotalPrice.html('');

		orderSummaryTotalPrice.append(response.result.total_price);
		
		
		$.each(response, function(index, item){
			if(index != 'result'){
			//	console.log(index);
				orderSummaryList.append('<div class="order-list-title" data-order-list-title="'+index+'"></div>');
				
				$.each(item, function(iindex, iitem){
				//	console.log(iindex);
					
					var getMatrixImgSrc=$('.matrix-row[data-matrix-class="'+iindex+'"] .first-col img').attr('src');
					orderSummaryList.append('<div class="selected-addon-title"><img src="'+getMatrixImgSrc+'"><span>'+iindex+'</span></div>');
					orderSummaryListTable=$('<table><tr><th>Option</th><th>qty</th><th>Price</th><tr></table>');
					
					$.each(iitem, function(iiindex, iiitem){
						$('.order-list-title[data-order-list-title="'+iiitem.option_name+'"]').html(iiitem.option_label.replace(/\\/g, ''));
						orderSummaryListTable.append('<tr><td>'+iiitem.option_value+'</td>'+'<td>'+iiitem.qty+'</td>'+'<td><span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol();?></span>'+iiitem.total_price+'</td></tr>');
					});
					
					orderSummaryList.append(orderSummaryListTable);
				});
			}
		});

		orderSummaryList.append(response.result.addon_cart_input);
//		console.log(response.result.total_price);
		if($('.matrix-dynamic-row').length == 0){
			$('button[name="add-to-cart"]').prop('disabled', true);
			$('input.wc_quick_buy_button').prop('disabled', true);
		}
	}

	//reset form data
	$('#reset-all-addons').on('click', function(){
		window.location.reload();
	});
})(jQuery);
</script>
<?php
}

// -----------------------------------------
// 2. Throw error if custom input field empty

add_filter( 'woocommerce_add_to_cart_validation', 'bbloomer_product_add_on_validation', 10, 3 );
function bbloomer_product_add_on_validation( $passed, $product_id, $qty ){
	if( isset( $_POST['sun_selected_add_ons'] ) && $_POST['sun_selected_add_ons'] == '' ) {
		wc_add_notice( __('Please select options', 'twentynineteen-child'), 'error' );
		$passed = false;
	}
	return $passed;
}

// -----------------------------------------
// 3. Save custom input field value into cart item data

add_filter( 'woocommerce_add_cart_item_data', 'bbloomer_product_add_on_cart_item_data', 10, 2 );

function bbloomer_product_add_on_cart_item_data( $cart_item, $product_id ){
	if( isset( $_POST['sun_selected_add_ons'] ) ) {
		$product = wc_get_product($product_id);
		
		$cart_item['sun_prd_addon_id'] = $_POST['prd_addon_id'];
		$cart_item['sun_selected_add_ons_str'] = $_POST['sun_selected_add_ons'];
		$cart_item['sun_selected_add_ons'] = sun_sort_product_addon('', $cart_item['sun_prd_addon_id'], $cart_item['sun_selected_add_ons_str']);
//		$cart_item['data']->set_weight(20);
//		$cart_item['weight']['default'] = $product->get_weight();
//		$cart_item['weight']['sun_total_weight']= 20;
//		$cart_item['total_price']=sun_sort_product_addon('total_price',$cart_item_data);
//		$addon_item_data=json_decode(stripslashes($_POST['sun_selected_add_ons']), true);
	}
	
	return $cart_item;
}

/**price weight**/
add_filter( 'woocommerce_add_cart_item', 'set_custom_cart_item_prices', 20, 2 );
function set_custom_cart_item_prices( $cart_data, $cart_item_key ) {
	// Price calculation
	$prd_addon_data=$cart_data['sun_selected_add_ons_str'];
	$sun_prd_addon_id=$cart_data['sun_prd_addon_id'];
	$total_price=sun_sort_product_addon('total_price', $sun_prd_addon_id, $prd_addon_data);
	$total_weight=sun_sort_product_addon('total_weight', $sun_prd_addon_id, $prd_addon_data);
	//	var_dump($total_price);
	// Set and register the new calculated price
	$cart_data['total_price'] = (float)$total_price;
	$cart_data['data']->set_price($cart_data['total_price']);	
	$cart_data['total_weight'] = (float)$total_weight;
	$cart_data['data']->set_weight($cart_data['total_weight']);
	
	return $cart_data;
}

//상품명에 무게 표시
add_filter( 'woocommerce_cart_item_name', 'sun_woocommerce_cart_item_display_weight', 10, 2 );
function sun_woocommerce_cart_item_display_weight($title, $cart_item){
	$weight_unit = get_option( 'woocommerce_weight_unit' );
	if(isset($cart_item['total_weight']) && !empty($cart_item['total_weight'])  && $cart_item['total_weight']!=0){	//옵션 있는 상품
		
		$item_total_weight = number_format((float)$cart_item['total_weight'], 2);
		
		$suffix_weight = "$item_total_weight $weight_unit";
		$title_data['weight'] = $suffix_weight;
		
		if ( ! empty( $title_data ) ) {
			$title.= ' (';
			$title.=$title_data['weight'];
			$title .= ')';
		}
	}
	else{	//일반상품
		$item_weight = number_format($cart_item['data']->get_weight(), 2);
		$suffix_weight = "$item_weight $weight_unit";
		
		if ( ! empty( $item_weight) ) {
			$title.= ' (';
			$title.=$suffix_weight;
			$title .= ')';
		}
	}
	
	return $title;
}
// -----------------------------------------
// 4. Display custom input field value @ Cart



add_filter( 'woocommerce_get_item_data', 'bbloomer_product_add_on_display_cart', 10, 2 );

function bbloomer_product_add_on_display_cart( $_data, $cart_item ) {
	if ( isset( $cart_item['sun_selected_add_ons'] ) ){
//		$data=$cart_item['sun_selected_add_ons'];
//		$sun_prd_addon_id=$cart_item['sun_prd_addon_id'];
//		$data=sun_sort_product_addon('addon', $sun_prd_addon_id, $cart_item_data);
		
		/*$data[] = array(
				'name' => 'Custom Text Add-On',
				'value' => $cart_item['sun_selected_add_ons']
		);*/
	}
//	var_dump($cart_item['data']->get_weight());
//	var_dump($cart_item['total_weight']);
/*	$test=$cart_item['sun_selected_add_ons'];
	foreach ($test as $testrow){
		echo $testrow['name'];
		
	}
	*/
	//var_dump($cart_item);
	/*$prd_addon_data=$cart_item['sun_selected_add_ons'];
	var_dump($prd_addon_data);
	foreach ($prd_addon_data as $data){
		var_dump($data['name']);
	}*/
	return $cart_item['sun_selected_add_ons'];
}



add_filter( 'woocommerce_get_cart_item_from_session', 'set_custom_cart_item_prices_from_session', 20, 3 );
function set_custom_cart_item_prices_from_session( $session_data, $values, $key ) {
	if ( ! isset( $session_data['total_price'] ) || empty ( $session_data['total_price'] ) )
		return $session_data;
		
	// Get the new calculated price and update cart session item price
	$session_data['data']->set_price( $session_data['total_price'] );
	$session_data['data']->set_weight( $session_data['total_weight'] );
	
	return $session_data;
}

// Updating cart item price
/*add_action( 'woocommerce_before_calculate_totals', 'change_cart_item_price', 30, 1 );
function change_cart_item_price( $cart ) {
	if ( ( is_admin() && ! defined( 'DOING_AJAX' ) ) )
		return;
		
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
			return;
			
			// Loop through cart items
			foreach ( $cart->get_cart() as $cart_item ) {
				// Set the new price
				if( isset($cart_item['custom_price']) ){
					$cart_item['data']->set_price($cart_item['custom_price']);
				}
			}
}
*/
// -----------------------------------------
// 5. Save custom input field value into order item meta

add_action( 'woocommerce_checkout_create_order_line_item', 'bbloomer_product_add_on_order_item_meta', 20, 4 );

function bbloomer_product_add_on_order_item_meta( $item, $cart_item_key, $values, $order ) {
	if ( ! empty( $values['sun_selected_add_ons'] ) ) {
		$prd_addon_data=$values['sun_selected_add_ons'];
		//$order_addon_arr=sun_sort_product_addon('', '', $prd_addon_data);
		$meta_number = 1;
		foreach ($prd_addon_data as $data){
			$item->update_meta_data( $meta_number.'. '.$data['name'], $data['value'] );
			$meta_number++;
		}
	}
}

// -----------------------------------------
// 6. Display custom input field value into order table

add_filter( 'woocommerce_order_item_product', 'bbloomer_product_add_on_display_order', 10, 2 );

function bbloomer_product_add_on_display_order( $cart_item, $order_item ){
	if( isset( $order_item['sun_selected_add_ons'] ) && !empty($order_item['sun_selected_add_ons'])){
		$cart_item_meta['sun_selected_add_ons'] = $order_item['sun_selected_add_ons'];
	}
	return $cart_item;
}

// -----------------------------------------
// 7. Display custom input field value into order emails

add_filter( 'woocommerce_email_order_meta_fields', 'bbloomer_product_add_on_display_emails' );

function bbloomer_product_add_on_display_emails( $fields ) {
	$fields['custom_text_add_on'] = 'Custom Text Add-On';
	return $fields;
}

//입력받은 상품옵션 메타데이터 정리
function sun_sort_product_addon($action, $prd_addon_id, $addon_data){
	$cart_item_data=json_decode(stripslashes($addon_data),true);
//	var_dump($cart_item_data);
//	$cart_item_data=$addon_data;
	$cart_item_data_len = count($cart_item_data);
	//var_dump($cart_item_data);
	//옵션명 추출
	$matrix_option_name=array();
	$matrix_option_name_n=0;
	/*foreach($form_data as $form_data_number => $form_data_row){
	 $matrix_option_name[$matrix_option_name_n++]=$form_data_row['option_name'];
	 
	 }*/
	$matrix_option_name=array_column($cart_item_data, 'option_name');
	$matrix_option_name=array_unique($matrix_option_name);	//중복값 제거
	$matrix_option_name=array_values($matrix_option_name);	//재인덱싱
	
	$matrix_option_name_len=count($matrix_option_name);
	
	$addon_list_by_matrix_option_name=array();
	$temp_form_data_row=array();
	//$key = array_search($matrix_option_name, array_column($cart_item_data, 'option_name'));
	/**가격, 무게 가져오기**/
	if(!empty($prd_addon_id)){
		$get_all_prd_addons = get_field_objects($prd_addon_id);
	}
	
	$all_prd_addons = $get_all_prd_addons['prd_option_setting']['value'];
	$all_prd_addon_matrix = array();
	//print_r($all_prd_addons);
	foreach($all_prd_addons as $all_prd_addon_row){
		if($all_prd_addon_row['option_type'] == 'matrix') {
			$all_prd_addon_matrix[]=$all_prd_addon_row;
		}
	}
//	var_dump($all_prd_addon_matrix);
	$key_price_length_weight= array('price', 'total_price', 'length', 'weight', 'total_weight'); //가져올 데이터의 키
	$value_price_length_weight=array(); //가져올 데이터 배열
	
	for($mon_n=0; $mon_n<$matrix_option_name_len; $mon_n++){
		//$searched_mon[$mon_n]= array_keys($matrix_option_name[$mon_n], array_column($form_data, 'option_name'));
		$temp_form_data_row=array();
		
		foreach($cart_item_data as $cart_item_data_number => $cart_item_data_row){
			//print_r($matrix_option_name[$mon_n]);
			if($cart_item_data_row['option_name'] == $matrix_option_name[$mon_n]){
				$temp_form_data_row[]=$cart_item_data_row;
				//$addon_list_by_matrix_option_name[$matrix_option_name[$mon_n]]=$temp_form_data_row;
			}
		}
//		var_dump($temp_form_data_row);
		/**가격, 무게 가져오기**/
		for($apam_n=0; $apam_n<count($all_prd_addon_matrix); $apam_n++){
			foreach($temp_form_data_row as $temp_form_data_key => $temp_form_data_row_data){
			//	var_dump($temp_form_data_row_data);
				if($temp_form_data_row_data['option_name'] == $all_prd_addon_matrix[$apam_n]['option_name']['option_name_value']){
					$sub_option_key = array_search($temp_form_data_row_data['option_value'], array_column($all_prd_addon_matrix[$apam_n]['sub_options'], 'sub_option_name'));
					$sub_option_price = $all_prd_addon_matrix[$apam_n]['sub_options'][$sub_option_key]['price_rate'];
					$sub_option_totalprice = number_format((float)$sub_option_price*(int)$temp_form_data_row_data['qty'], wc_get_price_decimals());
					$sub_option_length = $all_prd_addon_matrix[$apam_n]['sub_options'][$sub_option_key]['length'];
					$sub_option_weight = $all_prd_addon_matrix[$apam_n]['sub_options'][$sub_option_key]['weight'];
					$sub_option_totalweight =  number_format((float)$sub_option_weight*(int)$temp_form_data_row_data['qty'], wc_get_price_decimals());
					$value_price_length_weight=array($sub_option_price, $sub_option_totalprice, $sub_option_length, $sub_option_weight);
					
					$temp_form_data_row[$temp_form_data_key]['price'] = $sub_option_price;
					$temp_form_data_row[$temp_form_data_key]['total_price'] = $sub_option_totalprice;
					$temp_form_data_row[$temp_form_data_key]['length'] = $sub_option_length;
					$temp_form_data_row[$temp_form_data_key]['weight'] = $sub_option_weight;
					$temp_form_data_row[$temp_form_data_key]['total_weight'] = $sub_option_totalweight;
				}
			}
			//	var_dump($all_prd_addon_matrix[$apam_n]['option_name_value']);
			//}
			//print_r($temp_form_data_row);
		}
		
		$addon_list_by_matrix_option_name[$matrix_option_name[$mon_n]]=$temp_form_data_row;
	}
	//print_r($addon_list_by_matrix_option_name);
	
	
	
	$matrix_class_name=array(); //매트릭스클래스
	$addon_list_by_matrix_class=array(); //매트릭스 클래스 별 배열
	$selected_addon_list=array(); //최종 옵션 배열
	foreach($addon_list_by_matrix_option_name as $addon_list_by_matrix_option_name_key => $addon_list_by_matrix_option_name_row){
		
		$matrix_class_name=array_column($addon_list_by_matrix_option_name_row, 'matrix_class');
		$matrix_class_name=array_unique($matrix_class_name);
		$matrix_class_name=array_values($matrix_class_name);
		$matrix_class_name_len=count($matrix_class_name);
		
		$addon_list_by_matrix_class=array();
		$temp_matrix_data_row=array();
		
		for($mcn_n=0; $mcn_n<$matrix_class_name_len; $mcn_n++){
			$temp_matrix_data_row=array();
			foreach($addon_list_by_matrix_option_name_row as $addon_list_by_matrix_option_name_innerrow){
				if($addon_list_by_matrix_option_name_innerrow['matrix_class'] == $matrix_class_name[$mcn_n]){
					//number_format((float)$sub_option_price*(int)$temp_form_data_row_data['qty'], 2);
					$temp_matrix_data_row[]=$addon_list_by_matrix_option_name_innerrow;
					$addon_list_by_matrix_class[$matrix_class_name[$mcn_n]]=$temp_matrix_data_row;
				}
			}
		}
		
		$selected_addon_list[$addon_list_by_matrix_option_name_key]=$addon_list_by_matrix_class;
	}
//	print_r($selected_addon_list);
	
	/**상품 값 계산**/
	$total_price=0;
	$total_weight=0;
	foreach ($selected_addon_list as $selected_addon_list_row){
		foreach($selected_addon_list_row as $selected_addon_list_innerrow){
			foreach ($selected_addon_list_innerrow as $selected_addon_list_detail){
				$total_price += str_replace(',', '', $selected_addon_list_detail['total_price']);
				$total_weight += str_replace(',', '', $selected_addon_list_detail['total_weight']);
			}
		}
	}
	
	$addon_data_list_n=0;
	$addon_data_list=[];
	$addon_data_list_value=[];
	$addon_content='';
	foreach ($selected_addon_list as $selected_addon_row_key => $selected_addon_row){
		$addon_content='';
		foreach ($selected_addon_row as $selected_addon_row_value){
			foreach ($selected_addon_row_value as $addon_data_list_row){
				$addon_data_list[]=$addon_data_list_row;
			}
		}
		
		
		/*array(
		 'name' => $selected_addon_row_key,
		 'value' => $selected_addon_row_key
		 );*/
		
		$addon_data_list_n++;
	}
	for($i=0; $i<count($addon_data_list); $i++){
		$option_number=$i+1;
		$addon_data_result[$i]['name']=$addon_data_list[$i]['matrix_class'].' , '.$addon_data_list[$i]['option_label'];
		$addon_data_result[$i]['value']=$addon_data_list[$i]['option_value'].' × '.$addon_data_list[$i]['qty'].'EA';
		//		$addon_data_result[$i]['value']
	}
	//var_dump($total_price);
	//	var_dump($addon_data_result);
	if($action == 'total_price'){
		return $total_price;
	}
	elseif($action == 'total_weight'){
		return $total_weight;
	}
	else{
		return $addon_data_result;
	}
	
}

/**옵션 복사**/
/*
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */
function rd_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
	
	/*
	 * Nonce verification
	 */
	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
		return;
		
		/*
		 * get the original post id
		 */
		$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
		/*
		 * and all the original post data then
		 */
		$post = get_post( $post_id );
		
		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;
		
		/*
		 * if post data exists, create the post duplicate
		 */
		if (isset( $post ) && $post != null) {
			
			/*
			 * new post data array
			 */
			$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
			);
			
			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );
			
			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}
			
			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
			if (count($post_meta_infos)!=0) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ($post_meta_infos as $meta_info) {
					$meta_key = $meta_info->meta_key;
					if( $meta_key == '_wp_old_slug' ) continue;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
				$sql_query.= implode(" UNION ALL ", $sql_query_sel);
				$wpdb->query($sql_query);
			}
			
			
			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
			exit;
		} else {
			wp_die('Post creation failed, could not find original post: ' . $post_id);
		}
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );

/*
 * Add the duplicate link to action list for post_row_actions
 */
function rd_duplicate_post_link( $actions, $post ) {
	if ($post->post_type=='product_add_on' && current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
	}
	return $actions;
}
add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );