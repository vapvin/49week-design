<?php

include 'db.php';

$check_query = "SELECT * from company";
$result=mysqli_query($conn,$check_query);


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
                    <h1>업체관리</h1>
                </div>
                <ul class="breadcrumb">
                    <li>회원관리</li>
                    <li class="current-path">업체관리</li>
                </ul>
            </div>
            <!-- 페이지 타이틀 -->
            <div class="main-dashboard">
                <div class="container-row mt30 category-main">
                    <div class="colum-container container sidebar category-sidebar group-sidebar">
                        <div class="sidebar-content tabs">
                            <h3>업체 목록</h3>
                            <div id="Target_HW_type" class="tab-content page_side_cont_wrap">
                                <div class="panel">
                                    <!-- panel -->
                                    <div class="panel-big-body-wrap">
                                        <div id="category-layoutBoard">
                                            <div class="card-table">
                                                <table class="type09 type-category">
                                                    <thead>
                                                    <tr>
                                                        <th scope="cols">No.</th>
                                                        <th scope="cols">업체 명</th>
                                                        <th scope="cols"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php

                                                        if(mysqli_affected_rows($conn) > 0){



                                                            while ($row = mysqli_fetch_assoc($result)){


                                                                ?>
                                                                <tr>
                                                                    <td class="notice-num"><?php echo $row['idx']; ?></td>
                                                                    <td><?php echo $row['name']; ?></td>
                                                                    <th scope="row">
                                                                        <button data-idx="<?php echo $row['idx']; ?>" data-name="<?php echo $row['name']; ?>" class="edit-button">수정</button>
                                                                        <button data-idx="<?php echo $row['idx']; ?>" data-name="<?php echo $row['name']; ?>" class="det">삭제</button>
                                                                    </th>
                                                                </tr>
                                                                <?php
                                                                    
                                                            }
                                                                                            
                                                        }

                                                    ?>
                                                    
                                                    <!-- <tr>
                                                        <td class="notice-num">3</td>
                                                        <td>업체2</td>
                                                        <th scope="row">
                                                            <button>수정</button>
                                                            <button class="det">삭제</button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td class="notice-num">2</td>
                                                        <td>업체3</td>
                                                        <th scope="row">
                                                            <button>수정</button>
                                                            <button class="det">삭제</button>
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
                        <div class="colum-container col_main_board_wrap category_board_wrap">
                            <h3>업체 수정</h3>
                            <div class="category-cont">
                                <!-- <form class="edit-form" name="edit" action="company_update.php" method="POST">
                                    <div class="category-title">
                                        <div class="category-titlebox">
                                            <h3>업체 명</h3>
                                            <input require type="hidden" class="idx-input" name="idx">

                                            <input require type="text" class="edit-input" name="editcompany" >
                                        </div>
                                    </div>
                                    <div class="category-btn">
                                        <button class="edit-buttons">저장</button>
                                    </div>
                                <form> -->
                            </div>
                        </div>
                        <div class="colum-container col_main_board_wrap category_board_wrap">
                            <h3>업체 추가</h3>
                            <div class="category-cont">
                                <form class="add-form" name="add" action="company_add.php" method="POST">
                                    <div class="category-title">
                                        <div class="category-titlebox">
                                            <h3>업체 명</h3>
                                            <input name="company" type="text">
                                        </div>
                                    </div>
                                    <div class="category-btn">
                                        <button class="add-buttons">추가</button>
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

    const addForm = document.querySelector(".add-form");
    const editForm = document.querySelector(".edit-form");
    console.log(addForm)

    const addButtons = document.querySelector(".add-buttons");
    const editButtons = document.querySelector(".edit-buttons");

    addButtons.addEventListener("click", function(e){
        e.preventDefault();
        addForm.submit();
    })

    editButtons.addEventListener("click", function(e){
        e.preventDefault();
        editForm.submit();
    })
    
    const idxInput = document.querySelector(".idx-input");
    const editInput = document.querySelector(".edit-input");
      
      function edit(e){
        const dataIdx = e.target.dataset.idx;
        const dataName = e.target.dataset.name;
        idxInput.value = dataIdx;
        editInput.value = dataName;


      }
        const editButton = document.querySelectorAll(".edit-button");
        editButton.forEach(item => {
            item.addEventListener("click", edit);
        })
    </script>
</body>

</html>
