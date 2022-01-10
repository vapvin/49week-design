<?php
include 'db.php';

// $check_query = "SELECT * from company";
// $result=mysqli_query($conn,$check_query);
$comp = $_GET["company"];
$design_type = $_GET["type"];
$title = $_GET["title"];

session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION['company']) || !isset($_SESSION['user_name'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php'; </script>";
}


$check_query_image = "SELECT * from images WHERE type='{$design_type}' AND company='{$comp}'";
$image_result=mysqli_query($conn,$check_query_image);


$check_query_image_current = "SELECT * from images WHERE type='{$design_type}' AND company='{$comp}' AND title='{$title}'";
$current_image_result=mysqli_query($conn,$check_query_image_current);
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
   
</head>

<body class="main">
    <!-- wrap -->
    <div class="wrap">
        <!--header-->
        <header id="header">
            <?php include "header_front.php"; ?>
        </header>
        <!--header-->
        <!-- content -->
        <div class="content">
            <!-- 페이지 타이틀 -->
            <div class="page-title-bar container sub-header-container">
                <div class="page-title">
                    <ul class="sub-header">
                        <?php

                            if(mysqli_affected_rows($conn) > 0){



                                while ($row = mysqli_fetch_assoc($image_result)){

                                    if($comp == $row['company']) {
                                        ?>
                                        <li class="menu-item">
                                            <a href="design.php?company=<?php echo $comp; ?>&type=<?php echo $design_type; ?>&title=<?php echo $row["title"]; ?>"><?php echo $row["title"]; ?></a>
                                        </li>
                                        <?php
                                    } 
                                }
                                                                
                            }
                        ?>
                        
                        
                        
                    </ul>
                </div>
            </div>
            <script type="text/javascript" async="async">
                $(document).ready(function() {
                    $('li.menu-item').on('mouseenter', function() {
                        if ($('li.menu-item').has('ul.sub-header')) {
                            $(this).addClass('menu-visible');
                        }
                    });
                    $('li.menu-item').on('mouseleave', function() {
                        $(this).removeClass('menu-visible');
                    });
                });
            
            </script>
            <div class="sian-cont">

                <?php 
                $cur_image = mysqli_fetch_assoc($current_image_result);
                if(!isset($title)){
                    ?>

                    <h1>시안을 선택해주세요.</h1>

                    <?php
                } else {
                    ?>

                    <img src="<?php echo $cur_image["image_url"]; ?>" alt="" class="sian-main">


                    <?php
                }

                ?>
            </div>
            <!-- main-dashboard -->
        </div>
        <!-- content -->
    </div>
    <!-- wrap -->
    <!-- modal -->
</body>

</html>
