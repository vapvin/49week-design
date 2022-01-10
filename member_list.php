<?php
session_start();
include 'db.php';


session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION['company']) || !isset($_SESSION['user_name'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php'; </script>";
} else if($_SESSION['company'] != "49week"){

    echo "<script>alert('접근하실 수 없는 페이지입니다..'); history.back(); </script>";
}

$check_query = "SELECT * from user";
$result=mysqli_query($conn,$check_query);

$check_query_company = "SELECT * from company";
$result2=mysqli_query($conn,$check_query_company);

$check_query_company_edit = "SELECT * from company";
$result3=mysqli_query($conn,$check_query_company_edit);


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="./js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" type="text/css" media="all">
    <link rel="stylesheet" href="./js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.css" type="text/css" media="all">
    <link rel="stylesheet" href="./js/pick_me_up/css/pickmeup.css" type="text/css" media="all">
    <script src="./js/pick_me_up/dist/pickmeup.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/animate.css" type="text/css" media="all">
    <link rel="stylesheet" href="./css/style.css" type="text/css" media="all">
    <script src="./js/common.js" async="async"></script>
    <title>49week - admin</title>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#header").load("header.php");
        });
    </script>
</head>

<body class="8-1-1">
    <!-- wrap -->
    <div class="wrap">
        <!--header-->
        <header id="header"></header>
        <!--header-->
        <!-- content -->
        <div class="content">
            <!-- 페이지 타이틀 -->
            <div class="page-title-bar container">
                <div class="page-title">
                    <h1>회원목록</h1>
                </div>
                <ul class="breadcrumb">
                    <li>회원관리</li>
                    <li class="current-path">회원목록</li>
                </ul>
            </div>
            <!-- 페이지 타이틀 -->
            <div class="main-dashboard">
                <div class="container-row mt30 category-main member-main">
                    <div class="colum-container container sidebar category-sidebar member-sidebar">
                        <div class="sidebar-content tabs">
                            <h3>회원목록</h3>
                            <div id="Target_HW_type" class="tab-content page_side_cont_wrap">
                                <div class="panel">
                                    <!-- panel -->
                                    <div class="panel-big-body-wrap">
                                        <div id="category-layoutBoard">
                                            <div class="card-table">
                                                <table class="type09 type-category type-member">
                                                    <thead>
                                                    <tr>
                                                        <th scope="cols">No.</th>
                                                        <th scope="cols">ID</th>
                                                        <th scope="cols">이름</th>
                                                        <th scope="cols">업체</th>
                                                        <th scope="cols"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php

                                                        if(mysqli_affected_rows($conn) > 0){


                                                            $count = 0;

                                                            while ($row = mysqli_fetch_assoc($result)){
                                                                $count++;

                                                                ?>
                                                                <tr>

                                                                    <td class="notice-num member-num"><?php echo $count; ?></td>
                                                                    <td><?php echo $row['email']; ?></td>
                                                                    <td><?php echo $row['username']; ?></td>
                                                                    <td><?php echo $row['company']; ?></td>
                                                                    <th scope="row">
                                                                        <button data-idx="<?php echo $row['idx']; ?>" data-username="<?php echo $row['username']; ?>" data-email="<?php echo $row['email']; ?>" data-company="<?php echo $row['company']; ?>" data-password="<?php echo $row['password']; ?>" class="edit-button">수정</button>
                                                                        <button class="user-delete-button" data-idx="<?php echo $row['idx']; ?>" data-username="<?php echo $row['username']; ?>" data-email="<?php echo $row['email']; ?>" data-company="<?php echo $row['company']; ?>" data-password="<?php echo $row['password']; ?>" class="edit-button">삭제</button>
                                                                    </th>
                                                                    
                                                                </tr>
                                                                <?php
                                                            }
                                                                                            
                                                        }

                                                    ?>
                                                    <!-- <tr>
                                                        <td class="notice-num member-num">4</td>
                                                        <td>admin@49week.com</td>
                                                        <td>admin</td>
                                                        <td>업체1</td>
                                                        <th scope="row">
                                                            <button>수정</button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="notice-num member-num">3</td>
                                                        <td>member1@naver.com</td>
                                                        <td>회원1</td>
                                                        <td>업체1</td>
                                                        <th scope="row">
                                                            <button>수정</button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="notice-num member-num">2</td>
                                                        <td>member2@gmail.com</td>
                                                        <td>회원2</td>
                                                        <td>업체2</td>
                                                        <th scope="row">
                                                            <button>수정</button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="notice-num member-num">1</td>
                                                        <td>member3@daum.net</td>
                                                        <td>회원3</td>
                                                        <td>업체3</td>
                                                        <th scope="row">
                                                            <button>수정</button>
                                                        </th>
                                                    </tr> -->
                                                    
                                                    </tbody>
                                                </table>
                                                <div class="pageingnum">
                                                    <a href="#" class="pageing">1</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- panel -->
                            </div>
                            <!-- .tab-content -->
    
                        </div>
                    </div>
                    <!-- sidebar -->
                    <div class="categoyr-wrap">
                        
                        <div class="colum-container col_main_board_wrap category_board_wrap notice-admin-wrap member-wrap member-wrap02">
                            <h3>회원 추가</h3>
                            <span class="custom-ico arrow_bottom_icon member-close"></span>
                            <span class="custom-ico arrow_right_icon member-open"></span>
                            <div class="category-cont">
                                <form action="user_add.php" method="POST">
                                    <div class="category-title">
                                        <div class="category-titlebox">
                                            <div class="memberbox">
                                                <div class="memberid">
                                                    <h4>ID</h4>
                                                    <input name="email" type="email" placeholder="ex) 49week@admin.com">
                                                </div>
                                                <div class="memberpw">
                                                    <h4>비밀번호</h4>
                                                    <div class="pwbox">
                                                        <input name="password" type="password">
                                                    </div>
                                                    <h4>비밀번호 확인</h4>
                                                    <div class="pwbox">
                                                        <input name="password2" type="password">
                                                    </div>
                                                </div>
                                                <div class="membername">
                                                    <h4>이름</h4>
                                                    <input name="username" type="text">
                                                </div>
                                                <div class="membergroup">
                                                    <h4>업체</h4>
                                                    <input name="company" type="text" id="membergroup-set">
                                                    <!-- <select name="company" id="membergroup-set">
                                                    
                                                    </select> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="category-btn">
                                        <button>추가</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="colum-container col_main_board_wrap category_board_wrap notice-admin-wrap member-wrap member-wrap01">
                            <h3>회원 수정</h3>
                            <div class="category-cont">
                                <form action="user_edit.php" method="POST">
                                    <div class="category-title">
                                        <div class="category-titlebox">
                                            <div class="memberbox">
                                                <div class="memberid">
                                                    <h4>ID</h4>
                                                    <input class="email-edit" name="emailedit" type="email" placeholder="admin@49week.com">
                                                    <input type="hidden" class="idx-edit" name="idx">

                                                </div>
                                                <div class="memberpw">
                                                    <h4>비밀번호</h4>
                                                    <div class="pwbox">
                                                        <input class="password-edit" name="passwordedit" type="password" placeholder="*********">
                                                    </div>
                                                    <h4>비밀번호 확인</h4>
                                                    <div class="pwbox">
                                                        <input class="password-edit2" name="passwordedit2" type="password" placeholder="*********">
                                                    </div>
                                                </div>
                                                <div class="membername">
                                                    <h4>이름</h4>
                                                    <input class="username-edit" name="usernameedit" type="text" placeholder="admin">
                                                </div>
                                                <div class="membergroup">
                                                    <h4>업체</h4>
                                                    <input class="company-edit" name="companyedit" type="text">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="category-btn">
                                        <button>저장</button>
                                        <!-- <button style="background-color: rgb(250, 40, 40); margin-left: 5px;" class="det">삭제</button> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- colum-container -->
                </div>
                <!-- container-row -->
            </div>
        </div>
        <!-- content -->
    </div>
    <!-- wrap -->
    <!-- modal -->
    <script>
        $(document).ready(function(){
            $('.member-close').click(function(){
                $('.member-close').css({'display':'none'});
                $('.member-open').css({'display':'block'});
                $('.member-wrap02').animate({
                    height : '4vh'
                });
            });
            $('.member-open').click(function(){
                $('.member-open').css({'display':'none'});
                $('.member-close').css({'display':'block'});
                $('.member-wrap02').animate({
                    height : '28vh'
                });
            });
        });

        const editButton = document.querySelectorAll(".edit-button");

        editButton.forEach(item => {
            item.addEventListener("click", function(e){
                const dataset = e.target.dataset;
                const idx = dataset.idx;
                const username = dataset.username;
                const password = dataset.password;
                const email = dataset.email;
                const company = dataset.company;

                const editEmail = document.querySelector(".email-edit");
                const editPassword = document.querySelector(".password-edit");
                const editPassword2 = document.querySelector(".password-edit2");
                const editUsername = document.querySelector(".username-edit");
                const editCompany = document.querySelector(".company-edit");


                editEmail.value = email;
                editPassword.value = password;
                editPassword2.value = password;
                editUsername.value = username;
                editCompany.value = company;



                
            })
        });


        const deleteButtons = document.querySelectorAll(".user-delete-button");

        deleteButtons.forEach(item => {
            item.addEventListener("click", function(e){
                const dataset = e.target.dataset;
                const idx = dataset.idx;
                const username = dataset.username;
                const password = dataset.password;
                const email = dataset.email;
                const company = dataset.company;


                $.ajax({
					url:'/user_delete.php', // 요청 할 주소
					async:true,// false 일 경우 동기 요청으로 변경
					type:'POST', // GET, PUT
					data: {
                        idx: idx,
						username: username,
						password:password,
						email:email,
						company:company,
					},// 전송할 데이터
					dataType:'text',// xml, json, script, html
					beforeSend:function(jqXHR) {},// 서버 요청 전 호출 되는 함수 return false; 일 경우 요청 중단
					success:function(jqXHR) {
                        console.log(jqXHR);
						alert("삭제되었습니다.");
						location.reload();
					},// 요청 완료 시
					error:function(jqXHR) {},// 요청 실패.
					complete:function(jqXHR) {}// 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
				});
                
            })
        })
        
        
    </script>
</body>

</html>
