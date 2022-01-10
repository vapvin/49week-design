
<?php

include 'db.php';

$check_query = "SELECT DISTINCT company from user";
$result=mysqli_query($conn,$check_query);
$comp = $_GET["company"];


session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION['company']) || !isset($_SESSION['user_name'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php'; </script>";
} else if($_SESSION['company'] != "49week"){

    echo "<script>alert('접근하실 수 없는 페이지입니다..'); history.back(); </script>";
}

$check_query_type = "SELECT * from types";
$type_result=mysqli_query($conn,$check_query_type);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="./js/pick_me_up/dist/pickmeup.min.js"></script>
    <link rel="stylesheet" href="./js/pick_me_up/css/pickmeup.css" type="text/css" media="all">
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

<body class="main">
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
                    <h1>이미지 관리</h1>
                </div>
            </div>
            
            <div class="main-dashboard">
                <!-- <div id="sidebar-in"></div> -->
                <!-- sidebar -->
                <div class="colum-container container default-board mainboard">
                    <div class="sidebar-content tabs">
                        <div class="mainset">
                            <h3>업체선택</h3>
                            <select name="cy-set" id="cy-set">
                            <?php

                                if(mysqli_affected_rows($conn) > 0){



                                    while ($row = mysqli_fetch_assoc($result)){

                                        if($comp == $row['company']) {
                                            ?>
                                            <option selected value="<?php echo $row['company']; ?>"><?php echo $row['company']; ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="<?php echo $row['company']; ?>"><?php echo $row['company']; ?></option>
                                            <?php
                                        }
                                        
                                            
                                    }
                                                                    
                                }

                            ?>
                            </select>
                        </div>
                        <div class="mainbox">


                        <form action="image_add.php" method="POST" enctype="multipart/form-data">
                            
                            <div class="ty-cont">
                                <input name="design" type="file" id="input-file">
                            </div>
                            <div class="ty-tit">
                                <input name="title" type="text" placeholder="title01">
                            </div>
                            <div class="ty-tit">
                                <input name="company" type="text" value="<?php echo $comp; ?>" placeholder="company">
                            </div>
                            <div class="ty-tit">
                                <input name="type" type="text" placeholder="type">
                            </div>

                            <div class="cont-main-ty cont-main-ty04">
                                        <div class="ty-plus">
                                            <button name="submit">추가</button>
                                        </div>
                                    </div> 
                        </form>








                        <?php
                        if(mysqli_affected_rows($conn) > 0){

                            $count = 1;

                            while ($row = mysqli_fetch_assoc($type_result)){
                                if($row["company"] == $comp){
                               ?>
                                     <div class="maincont maincont0<?php echo $count?>">
                                    <div class="cont-tit">
                                        <h3>Type <?php echo $row["name"];?> .</h3>
                                    </div>
                                    <?php



                                    $check_query_image = "SELECT * from images WHERE type='{$row["name"]}'";
                                    $image_result=mysqli_query($conn,$check_query_image);

                                    
                                    if(mysqli_affected_rows($conn) > 0){

                                        $count2 = 1;

                                        while ($row2 = mysqli_fetch_assoc($image_result)){
                                            if($row2["type"] == $row["name"] && $row2["company"] == $comp){
                                        ?>
                                                <div class="cont-main">
                                                    <div class="cont-main-ty cont-main-ty0<?php echo $count2; ?>">
                                                    <div class="ty-tit">
                                                            <h2><?php echo $row2["title"]; ?></h2>
                                                        </div>
                                                        <div class="ty-cont">
                                                                <img src="<?php echo $row2["image_url"]; ?>" alt="" class="uplord-img">
                                                        </div>

                                                        <div class="ty-cont">
                                                               <input type="hidden" name="type_edit" value="<?php echo $row["name"]; ?>">
                                                        </div>
                                                        <div class="ty-btn">
                                                            <button class="image-delete-button" data-type="<?php echo $row2["type"]; ?>" data-title="<?php echo $row2["title"]; ?>" data-company="<?php echo $row2["company"]; ?>">삭제</button>
                                                        </div>
                                                    </div>
                                        </div>

                    
                                        <?php
                                            $count2++;
                                                
                                            }
                                        }
                                                                        
                                    }
                                    
                                    ?>
                                            </div>
        
                               <?php
                                $count++;
                                    
                                }
                            }
                                                            
                        }
                        
                        ?>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                      
                          
                            <div class="save">
                                <form action="add_type.php" method="POST">
                                    <input type="hidden" name="type_compnay" value="<?php echo $comp; ?>">
                                    <input type="text" name="add_type">
                                    <button>Type 추가</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-dashboard -->
        </div>
        <!-- content -->
    </div>
    <!-- wrap -->
    <!-- modal -->

    <script>
        const fileInput = document.querySelector("#input-file");
        function getQueryStringObject() {
            var a = window.location.search.substr(1).split('&');
            if (a == "") return {};
            var b = {};
            for (var i = 0; i < a.length; ++i) {
                var p = a[i].split('=', 2);
                if (p.length == 1)
                    b[p[0]] = "";
                else
                    b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
            }
            return b;
        }

        var qs = getQueryStringObject();
        const companySelect = document.querySelector("#cy-set");

        if(!qs.company){
            const value = companySelect.value;
            location.href = "/index.php?company="+value;
        }


        companySelect.addEventListener("change", function(e){
            const value = e.target.value;
            location.href = "/index.php?company="+value;
        });

        const deleteButtons = document.querySelectorAll(".image-delete-button");

        deleteButtons.forEach(item => {
            item.addEventListener("click", function(e){
                const dataTitle = e.target.dataset.title;
                const dataType = e.target.dataset.type;
                const dataCompany = e.target.dataset.company;


                $.ajax({
					url:'/image_delete.php', // 요청 할 주소
					async:true,// false 일 경우 동기 요청으로 변경
					type:'POST', // GET, PUT
					data: {
						title: dataTitle,
						type:dataType,
						company:dataCompany,
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
                
            })
        })
        
        
    </script>
    
    
</body>

</html>


