<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>디자인 시안 A</title>
    <!-- 타이틀에 해당 디자인 이름? -->
    <link rel="stylesheet" href="css/design_detail.css">
</head>
<body>
  <header>
    <div class="wrap_menu">
        <div class="btn_menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="wrap_page_list">
            <nav class="list_page">
                <div class="inner_page_list">
                    <div class="list_main list">
                        <h3>메인</h3>
                        <a href="design_detail.php">A 시안 메인 페이지 1</a>
                        <a href="javascript:">A 시안 메인 페이지 2</a>
                    </div>
                    <div class="list_sub list">
                        <h3>서브</h3>
                        <a href="javascript:">A 시안 서브 페이지 1</a>
                        <a href="javascript:">A 시안 서브 페이지 2</a>
                        <a href="javascript:">A 시안 서브 페이지 3</a>
                    </div>
                    <a href="design_list.php" class="go_home">첫 화면으로</a>
                </div>
            </nav>
        </div>
    </div>
    <h2 class="list_title">A 디자인 ver.2 (요청 사항 반영)</h2>
    <div class="wrap_title">
        <div class="box_checks">
            <input type="radio" name="checked" value="" id="final_decision_1">
            <label for="final_decision_1"></label> 
            <button>확정하기</button>
        </div>
    </div>
  </header>
  <img src="img/poonglim_sample_1.jpg" alt="" class="body_img">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
      $(function(){
        $('.btn_menu').on('click', function(){
            $(this).toggleClass('active');
            if($(this).attr('data-click-state') == 1) {
                $(this).attr('data-click-state', 0);
                $('.list_page').fadeOut(300);
            }else{
                $(this).attr('data-click-state', 1);
                $('.list_page').fadeIn(300);
            }
            // slidedown은 자식에게 안먹힘.. 그래서 fadein으로 교체
        });
        $('.box_checks button').on('click', function(){
            let result = confirm("최종결정 하시겠습니까?");
            if(result){
                alert("디자인 선택이 최종결정 완료 되었습니다.");
            }
        });
        // let i = $('.inner_page_list .list div');
        // function tet(e){
        //     let index = e.target.dataset.index;
        //     $('.body_img').css("src","img/poonglim_sample_"+i[index]+".jpg")
        // }
        // $('.inner_page_list .list div').click(function(e){
            // e.preventDefault();
            // tet(e);
        // });
        // i.forEach((item,index) => console.log(item, index));
        // $('.inner_page_list .list div').each(function(index){
        //     $(this).click
        // })
      });

      
  </script>
</body>
</html>