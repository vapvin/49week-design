/* get <html> element */
var elem = document.documentElement;

/* 전체화면 */
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.mozRequestFullScreen) { /* Firefox */
    elem.mozRequestFullScreen();
  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE/Edge */
    elem.msRequestFullscreen();
  }
}

/*window.onload=function(){
	var today = new Date();
	var picker = tui.DatePicker.createRangePicker({
	    startpicker: {
	        date: today,
	        input: '#startpicker-input',
	        container: '#startpicker-container'
	    },
	    endpicker: {
	        date: today,
	        input: '#endpicker-input',
	        container: '#endpicker-container'
	    },
	    selectableRanges: [
	        [today, new Date(today.getFullYear() + 1, today.getMonth(), today.getDate())]
	    ]
	});
}*/

(function($){
	$(document).ready(function(){
		/**header**/
		$('li.menu-item').on('mouseenter',function(){
			if($('li.menu-item').has('ul.sub-menu')){
				$(this).addClass('menu-visible');
			}
			
		});
		$('li.menu-item').on('mouseleave',function(){
			$(this).removeClass('menu-visible');
		});
		
		/*modal*/
        $('.modal-btn').on('click', function(e) {
            e.preventDefult;
            var modal = $($(this).data('target'));
            console.log(modal);
            var animateIn = modal.data('in');
            var animateOut = modal.data('out');
            var animateSpeed = modal.data('speed');

            if (modal.hasClass('hide')) {
                modal.removeClass('hide');
                if (animateIn != 'undefined') {
                    if (animateOut != 'undefined' && modal.removeClass(animateOut)) {
                        modal.removeClass(animateOut);
                    }

                    if (animateSpeed != 'undefined') {
                        modal.addClass('animated ' + animateSpeed + ' ' + animateIn);
                    } else {
                        modal.addClass('animated ' + animateIn);
                    }
                }
            }
        });
        $('.modal .close').on('click', function(e) {
            var modal = $(this).parents('.modal');
            var animateIn = modal.data('in');
            var animateOut = modal.data('out');
            var animateSpeed = modal.data('speed');
            var animateTime;

            if (!modal.hasClass('hide')) {
                if (animateOut != 'undefined') {
                    if (animateIn != 'undefined' && modal.removeClass(animateIn)) {
                        modal.removeClass(animateIn);
                    }

                    if (animateSpeed != 'undefined') {

                        if (animateSpeed == 'fast') {
                            animateTime = 800;
                        } else if (animateSpeed == 'faster') {
                            animateTime = 500;
                        } else if (animateSpeed == 'slow') {
                            animateTime = 2000;
                        } else if (animateSpeed == 'slower') {
                            animateTime = 3000;
                        }

                        modal.addClass('animated ' + animateSpeed + ' ' + animateOut);
                        setTimeout(function() {
                            modal.addClass('hide');
                        }, animateTime);
                    } else {
                        modal.addClass('animated ' + animateOut);
                        setTimeout(function() {
                            modal.addClass('hide');
                        }, 1000);
                    }
                    modal.addClass('animated ' + animateOut);

                } else {
                    modal.addClass('hide');
                }
            }
        });
        //end modal
		
        /**tooltip**/
		$('.tooltip').tooltip({
			content: function(){
				return $(this).prop('title');
			}
		});
        
		/**collapse**/
		$('.collapsible').on('click', function(e){
			e.preventDefault();
			if($(this).hasClass('collapsed')){
				$(this).removeClass('collapsed');
				$($(this).data('target')).addClass('show');
			}
			else{
				$(this).addClass('collapsed');
				$($(this).data('target')).removeClass('show');
			}
		});
		
		/**tabs**/
		$('.tabs').tabs();
		
		/**필터드롭다운**/
		/* When the user clicks on the button,
		toggle between hiding and showing the dropdown content */
		$('.filter-dropdown-btn').on('click', function(){
			var dropdown=$(this).parents('.dropdown');
			dropdown.find('.filter-dropdown').toggleClass('show');
		});
		
		$('input.drop-search').on('keyup', function(){
			var input, dropdown;
			input = $(this).val();
			dropdown=$('.dropdown-content *');
			console.log(dropdown.text());
			$('.dropdown-content label').filter(function() {
				$(this).toggle($(this).text().indexOf(input) > -1)
			});
		});
		
		/**fullscreen**/
		$('#fullScreen').on('click', function(){
			openFullscreen();
		});
		
		/**play controler**/
		$('#playControl').on('click', function(e){
			e.preventDefault();
			if($(this).hasClass('play')){
				$(this).removeClass('play');
			}
			else{
				$(this).addClass('play');
			}
		});
		
		/**시간선택**/
		$('.selet-time').on('click', function(){
			console.log($(this));
			var id=$(this).attr('id');
			console.log(id);
			$('#timePicker').insertAfter($(this));
			$('#timePicker').attr('class', id+' show');
		});
		
		$('#timePicker .close').on('click', function(e){
			var selectPeriod = $(this).parents('#timePicker');
			selectPeriod.removeClass('show');
		});
		
		/**기간선택**/
		$('input[data-target=".selectPeriod"]').on('click', function(e) {
            var selectPeriod = $($(this).data('target'));
            selectPeriod.toggleClass('show');
        });
		
		$('.selectPeriod .close').on('click', function(e){
			var selectPeriod = $(this).parents('.selectPeriod');
			selectPeriod.removeClass('show');
		});
		
		/**table select**/
		$('#tpListTable').on( 'click', '.tp-title', function(){
			var tr=$(this).parents('tr');
			
	        if (tr.hasClass('selected')) {
	        	tr.removeClass('selected');
	        	$('#tpInfoPanel').hide();
	        }
	        else {
	        	$('#tpListTable tr').removeClass('selected');
	        	tr.addClass('selected');
	        	$('#tpInfoPanel').show();
	        }
	    } );
		
		$('#svcViewTable').on( 'click', '.svc-title', function(){
			var tr=$(this).parents('tr');
			
	        if (tr.hasClass('selected')) {
	        	tr.removeClass('selected');
	        }
	        else {
	        	$('#svcViewTable tr').removeClass('selected');
	        	tr.addClass('selected');
	        }
	    } );
		
		$('#mntrViewTable').on( 'click', '.mntr-title', function(){
			var tr=$(this).parents('tr');
			
	        if (tr.hasClass('selected')) {
	        	tr.removeClass('selected');
	        }
	        else {
	        	$('#mntrViewTable tr').removeClass('selected');
	        	tr.addClass('selected');
	        }
	    } );
		
		/*달력*/
		pickmeup.defaults.locales['ko']={
				days		: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
				daysShort	: ['일', '월', '화', '수', '목', '금', '토'],
				daysMin		: ['일', '월', '화', '수', '목', '금', '토'],
				months		: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
				monthsShort	: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
				meridiem : ["오전", "오후"]
		}
		
		var now = new Date();
		pickmeup('#searchDate', {
			hide_on_select : true,
			mode : "range",
			first_day: 0,
			format: 'Y-m-d',
			max: now,
			locale: 'ko',
		});
		
		/*datepicker, timepicker 한글*/
		$.datepicker.setDefaults({
	        dateFormat: 'yy-mm-dd',
	        prevText: '이전 달',
	        nextText: '다음 달',
	        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
	        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
	        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
	        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
	        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
	        showMonthAfterYear: true,
	        yearSuffix: '년'
	    });
		$.timepicker.setDefaults({
			timeOnlyTitle: '시간',
			timeText: '시간',
			hourText: '시',
			minuteText: '분',
			secondText: '초',
			millisecText: '밀리초',
			timezoneText: '타임존',
			currentText: '현재',
			closeText: '닫기',
			timeFormat: 'HH:mm:ss',
			isRTL: false
		});
		
		/*2_2_2 date range picker*/
		var startDate=$('#startpicker-input');
		var endDate=$('#endpicker-input');
		
		startDate.datetimepicker({
			controlType: 'select',
			onClose: function(dateText, inst) {
				if (endDate.val() != '') {
					var testStartDate = startDate.datetimepicker('getDate');
					var testEndDate = endDate.datetimepicker('getDate');
					if (testStartDate > testEndDate)
						endDate.datetimepicker('setDate', testStartDate);
				}
				else {
					endDate.val(dateText);
				}
			},
			onSelect: function (selectedDateTime){
				endDate.datetimepicker('option', 'minDate', startDate.datetimepicker('getDate') );
			},
			oneLine: true,
		});
		startDate.datetimepicker('setDate', (now));
		
		endDate.datetimepicker({
			controlType: 'select',
			onClose: function(dateText, inst) {
				if (startDate.val() != '') {
					var testStartDate = startDate.datetimepicker('getDate');
					var testEndDate = endDate.datetimepicker('getDate');
					if (testStartDate > testEndDate)
						startDate.datetimepicker('setDate', testEndDate);
				}
				else {
					startDate.val(dateText);
				}
			},
			onSelect: function (selectedDateTime){
				startDate.datetimepicker('option', 'maxDate', endDate.datetimepicker('getDate') );
			},
			oneLine: true,
		});
		endDate.datetimepicker('setDate', (now));
		
		$('.det').click(function(e){
			var result = confirm('정말로 삭제 하시겠습니까?'); 
			if(result){ //yes 
				const dataIdx = e.target.dataset.idx;
				const dataName = e.target.dataset.name;
				$.ajax({
					url:'/comapny_delete.php', // 요청 할 주소
					async:true,// false 일 경우 동기 요청으로 변경
					type:'POST', // GET, PUT
					data: {
						idx: dataIdx,
						name:dataName,
					},// 전송할 데이터
					dataType:'text',// xml, json, script, html
					beforeSend:function(jqXHR) {},// 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
					success:function(jqXHR) {
						alert("삭제되었습니다.");
						location.reload();
					},// 요청 완료 시
					error:function(jqXHR) {},// 요청 실패.
					complete:function(jqXHR) {}// 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
				});
				
			}else{ //no 
				return false;
			}
		});

	});//document ready
	
})(jQuery);


