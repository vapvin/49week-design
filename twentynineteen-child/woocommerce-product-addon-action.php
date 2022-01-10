<?php

add_action( 'wp_ajax_sun_prd_price_calc', 'sun_prd_price_calc' );
add_action( 'wp_ajax_nopriv_sun_prd_price_calc', 'sun_prd_price_calc' );
function sun_prd_price_calc() {
	check_ajax_referer( 'sun-prd-addon-secure', 'security' );
	//parse_str($_POST['data'], $form_data);
	//print_r($_POST['data']);
	if(isset($_POST['data']) && !empty($_POST['data'])){
		$form_data=$_POST['data'];	//옵션데이터
		$form_data_len=count($form_data); //옵션데이터 총길이
		
		$prd_id=$_POST['prd_id'];
		$prd_addon_id=$_POST['prd_addon_id'];
	//	$prd_addon_array=array();
	//	print_r($form_data);
	
		//옵션명 추출
		$matrix_option_name=array();
		$matrix_option_name_n=0;
		/*foreach($form_data as $form_data_number => $form_data_row){
			$matrix_option_name[$matrix_option_name_n++]=$form_data_row['option_name'];
			
		}*/
		$matrix_option_name=array_column($form_data, 'option_name');
		$matrix_option_name=array_unique($matrix_option_name);	//중복값 제거
		$matrix_option_name=array_values($matrix_option_name);	//재인덱싱
		
		$matrix_option_name_len=count($matrix_option_name);
		
		$addon_list_by_matrix_option_name=array();
		$temp_form_data_row=array();
		//$key = array_search($matrix_option_name, array_column($form_data, 'option_name'));
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
		
		$key_price_length_weight= array('price', 'total_price', 'length', 'weight'); //가져올 데이터의 키
		$value_price_length_weight=array(); //가져올 데이터 배열
		
		for($mon_n=0; $mon_n<$matrix_option_name_len; $mon_n++){
			//$searched_mon[$mon_n]= array_keys($matrix_option_name[$mon_n], array_column($form_data, 'option_name'));
			$temp_form_data_row=array();
			
			foreach($form_data as $form_data_number => $form_data_row){
				//print_r($matrix_option_name[$mon_n]);
				if($form_data_row['option_name'] == $matrix_option_name[$mon_n]){
					$temp_form_data_row[]=$form_data_row;
					//$addon_list_by_matrix_option_name[$matrix_option_name[$mon_n]]=$temp_form_data_row;
				}
			}
			
			/**가격, 무게 가져오기**/
			for($apam_n=0; $apam_n<count($all_prd_addon_matrix); $apam_n++){
				foreach($temp_form_data_row as $temp_form_data_key => $temp_form_data_row_data){
					if($temp_form_data_row_data['option_name'] == $all_prd_addon_matrix[$apam_n]['option_name']['option_name_value']){
						$sub_option_key = array_search($temp_form_data_row_data['option_value'], array_column($all_prd_addon_matrix[$apam_n]['sub_options'], 'sub_option_name'));
						$sub_option_price = $all_prd_addon_matrix[$apam_n]['sub_options'][$sub_option_key]['price_rate'];
						$sub_option_totalprice = number_format((float)$sub_option_price*(int)$temp_form_data_row_data['qty'], wc_get_price_decimals());
						$sub_option_length = $all_prd_addon_matrix[$apam_n]['sub_options'][$sub_option_key]['length'];
						$sub_option_weight = $all_prd_addon_matrix[$apam_n]['sub_options'][$sub_option_key]['weight'];
						$value_price_length_weight=array($sub_option_price, $sub_option_totalprice, $sub_option_length, $sub_option_weight);
						
						$temp_form_data_row[$temp_form_data_key]['price'] = $sub_option_price;
						$temp_form_data_row[$temp_form_data_key]['total_price'] = $sub_option_totalprice;
						$temp_form_data_row[$temp_form_data_key]['length'] = $sub_option_length;
						$temp_form_data_row[$temp_form_data_key]['weight'] = $sub_option_weight;
						//var_dump($sub_option_totalprice);
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
		foreach ($selected_addon_list as $selected_addon_list_row){
			foreach($selected_addon_list_row as $selected_addon_list_innerrow){
				foreach ($selected_addon_list_innerrow as $selected_addon_list_detail)
					$total_price += str_replace(',', '', $selected_addon_list_detail['total_price']);
			}
		}
		$total_price=wc_price($total_price);
		//장바구니를 위한 json data
		$add_on_data=array();
		
		for($i=0; $i<$form_data_len; $i++){
			$add_on_data[$i] = $form_data[$i];
		}
		
		
		$add_on_data_quary = stripslashes(json_encode($add_on_data, JSON_UNESCAPED_UNICODE)); //역슬래시 벗기기
		
		//$sun_prd_addon_input_hidden='<input type="hidden" name="sun_selected_add_ons" value="'.$add_on_data_quary.'">';
		$sun_prd_addon_input_hidden = "<input type='hidden' name='sun_selected_add_ons' value='".esc_attr($add_on_data_quary)."'>";
		$selected_addon_list['result']['total_price'] = $total_price;
		$selected_addon_list['result']['addon_cart_input'] = $sun_prd_addon_input_hidden;
		
		$add_on_result_data=json_encode($selected_addon_list, JSON_UNESCAPED_UNICODE);
		//var_dump($add_on_result_data);
		
		echo $add_on_result_data;
	}
	else{
		return false;
	}
	wp_die();
}